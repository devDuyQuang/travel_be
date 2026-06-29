<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentStatus;
use App\Mail\BookingReceivedMail;
use App\Models\Booking;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function __construct(
        private readonly PaymentService $payments,
        private readonly BookingPricingService $pricing,
        private readonly CustomerAccountService $customers,
    ) {}

    public function create(array $data, ?int $customerId = null): Booking
    {
        return $this->createBookingTransaction($data, fn () => [
            'customer_id' => $customerId,
            'account_created' => false,
            'account_setup_url' => null,
            'created' => true,
        ])['booking'];
    }

    public function createFromPublicRequest(array $data, ?int $authenticatedCustomerId = null): array
    {
        $result = $this->createBookingTransaction($data, function () use ($data, $authenticatedCustomerId) {
            if ($authenticatedCustomerId) {
                return [
                    'customer_id' => $authenticatedCustomerId,
                    'account_created' => false,
                    'account_setup_url' => null,
                ];
            }

            $account = $this->customers->resolveForBooking($data['customer_email'], $data['customer_name']);

            return [
                'customer_id' => $account['user']->id,
                'account_created' => $account['created'],
                'account_setup_url' => $account['setup_url'],
            ];
        });

        $mailDispatched = false;

        if ($result['created']) {
            try {
                Mail::to($result['booking']->customer_email)->send(new BookingReceivedMail(
                    $result['booking']->fresh(['customer']),
                    $result['account_setup_url'],
                ));
                $mailDispatched = true;
            } catch (\Throwable $exception) {
                Log::error('Failed to send booking received email.', [
                    'booking_id' => $result['booking']->id,
                    'booking_code' => $result['booking']->booking_code,
                    'customer_email' => $result['booking']->customer_email,
                    'exception' => $exception->getMessage(),
                ]);
            }
        }

        return [
            'booking' => $result['booking'],
            'created' => $result['created'],
            'account_created' => $result['account_created'],
            'mail_dispatched' => $mailDispatched,
        ];
    }

    private function createBookingTransaction(array $data, callable $resolveCustomer): array
    {
        return DB::transaction(function () use ($data, $resolveCustomer) {
            $payloadHash = $this->payloadHash($data);

            if (! empty($data['idempotency_key'])) {
                $existing = Booking::query()
                    ->where('idempotency_key', $data['idempotency_key'])
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    if ($existing->idempotency_payload_hash && $existing->idempotency_payload_hash !== $payloadHash) {
                        throw ValidationException::withMessages([
                            'idempotency_key' => 'Yêu cầu đã được gửi trước đó với dữ liệu khác. Vui lòng tải lại trang và thử lại.',
                        ]);
                    }

                    return [
                        'booking' => $existing->load(['payments', 'histories']),
                        'created' => false,
                        'account_created' => false,
                        'account_setup_url' => null,
                    ];
                }
            }

            $product = Product::query()
                ->with('category:id,name,slug,type,layout_key')
                ->whereKey($data['service_product_id'])
                ->where('status', 1)
                ->lockForUpdate()
                ->firstOrFail();

            if ($product->product_type !== 'service' || strtolower((string) $product->category?->type) !== 'service') {
                throw ValidationException::withMessages([
                    'service_product_id' => 'Sản phẩm này không phải dịch vụ có thể booking.',
                ]);
            }

            $bookingType = isset($data['booking_type'])
                ? BookingType::tryFrom((string) $data['booking_type'])
                : null;
            $bookingType ??= BookingType::fromLayout($product->category?->layout_key);
            $calculated = $this->pricing->calculate($product, $bookingType, $data);

            $adults = max(0, (int) ($data['adults'] ?? ($calculated['details']['adults'] ?? 0)));
            $children = max(0, (int) ($data['children'] ?? 0));
            $paymentStatus = ! empty($data['payment_method'])
                ? PaymentStatus::Pending->value
                : PaymentStatus::Unpaid->value;
            $customer = $resolveCustomer();

            $booking = Booking::create([
                'public_id' => (string) Str::ulid(),
                'booking_code' => $this->generateCode(),
                'idempotency_key' => $data['idempotency_key'] ?? null,
                'idempotency_payload_hash' => $payloadHash,
                'customer_id' => $customer['customer_id'],
                'service_product_id' => $product->id,
                'service_category_id' => $product->category_id,
                'booking_type' => $bookingType->value,
                'service_name_snapshot' => $product->name,
                'service_slug_snapshot' => $product->slug,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'start_time' => $data['start_time'] ?? null,
                'adults' => $adults,
                'children' => $children,
                'quantity' => $calculated['quantity'],
                'currency' => 'VND',
                'pricing_mode' => $calculated['pricing_mode']->value,
                'unit_price' => $calculated['unit_price'],
                'subtotal' => $calculated['subtotal'],
                'discount_amount' => $calculated['discount_amount'],
                'total_amount' => $calculated['total_amount'],
                'pricing_snapshot' => $calculated['snapshot'],
                'booking_details' => $calculated['details'],
                'customer_note' => $data['customer_note'] ?? null,
                'booking_status' => BookingStatus::Pending->value,
                'payment_status' => $paymentStatus,
                'payment_method' => $data['payment_method'] ?? null,
            ]);

            $booking->histories()->create([
                'from_status' => null,
                'to_status' => BookingStatus::Pending->value,
                'changed_by' => $customer['customer_id'],
                'note' => 'Khách gửi booking.',
            ]);

            $this->payments->createInitialPayment($booking, [
                'amount' => $calculated['total_amount'],
                'currency' => 'VND',
                'payment_method' => $data['payment_method'] ?? null,
            ]);

            return [
                'booking' => $booking->load(['payments', 'histories']),
                'created' => true,
                'account_created' => $customer['account_created'] ?? false,
                'account_setup_url' => $customer['account_setup_url'] ?? null,
            ];
        });
    }

    public function transition(Booking $booking, string $toStatus, ?string $note = null, ?string $cancelReason = null, ?int $changedBy = null): Booking
    {
        return DB::transaction(function () use ($booking, $toStatus, $note, $cancelReason, $changedBy) {
            $booking = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $from = $booking->booking_status instanceof BookingStatus
                ? $booking->booking_status->value
                : (string) $booking->booking_status;

            if (! in_array($toStatus, BookingStatus::allowedTransitions($from), true)) {
                throw ValidationException::withMessages([
                    'status' => 'Không thể chuyển trạng thái booking theo thao tác này.',
                ]);
            }

            $updates = ['booking_status' => $toStatus];

            if ($toStatus === BookingStatus::Confirmed->value) {
                $updates['confirmed_at'] = now();
            } elseif ($toStatus === BookingStatus::Completed->value) {
                $updates['completed_at'] = now();
            } elseif ($toStatus === BookingStatus::Cancelled->value) {
                $updates['cancelled_at'] = now();
                $updates['cancel_reason'] = $cancelReason;
            }

            $booking->update($updates);
            $booking->histories()->create([
                'from_status' => $from,
                'to_status' => $toStatus,
                'changed_by' => $changedBy,
                'note' => $toStatus === BookingStatus::Cancelled->value ? $cancelReason : $note,
            ]);

            return $booking->fresh(['histories', 'payments']);
        });
    }

    private function payloadHash(array $data): string
    {
        unset($data['idempotency_key']);
        ksort($data);

        return hash('sha256', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function generateCode(): string
    {
        do {
            $code = 'BK'.now()->format('ymd').strtoupper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }
}
