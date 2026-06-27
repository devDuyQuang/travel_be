<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PricingMode;
use App\Http\Requests\Admin\UpdateBookingQuoteRequest;
use App\Http\Requests\Admin\UpdateBookingStatusRequest;
use App\Http\Requests\Admin\UpdateInternalNoteRequest;
use App\Http\Requests\Admin\UpdatePaymentRequest;
use App\Models\Booking;
use App\Models\Category;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::query()
            ->select([
                'id',
                'booking_code',
                'customer_name',
                'customer_email',
                'customer_phone',
                'service_name_snapshot',
                'service_category_id',
                'booking_type',
                'pricing_mode',
                'start_date',
                'total_amount',
                'booking_status',
                'payment_status',
                'created_at',
            ])
            ->with('serviceCategory:id,name')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = trim((string) $request->keyword);
                $query->where(function ($builder) use ($keyword) {
                    $builder->where('booking_code', 'like', "%{$keyword}%")
                        ->orWhere('customer_name', 'like', "%{$keyword}%")
                        ->orWhere('customer_email', 'like', "%{$keyword}%")
                        ->orWhere('customer_phone', 'like', "%{$keyword}%")
                        ->orWhere('service_name_snapshot', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('booking_status'), fn ($query) => $query->where('booking_status', $request->booking_status))
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->payment_status))
            ->when($request->filled('service_category_id'), fn ($query) => $query->where('service_category_id', $request->integer('service_category_id')))
            ->when($request->filled('created_from'), fn ($query) => $query->whereDate('created_at', '>=', $request->created_from))
            ->when($request->filled('created_to'), fn ($query) => $query->whereDate('created_at', '<=', $request->created_to))
            ->when($request->filled('start_from'), fn ($query) => $query->whereDate('start_date', '>=', $request->start_from))
            ->when($request->filled('start_to'), fn ($query) => $query->whereDate('start_date', '<=', $request->start_to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $serviceCategories = Category::query()
            ->whereRaw('LOWER(type) = ?', ['service'])
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('booking.index', [
            'bookings' => $bookings,
            'serviceCategories' => $serviceCategories,
            'bookingStatuses' => BookingStatus::options(),
            'paymentStatuses' => PaymentStatus::options(),
            'bookingTypes' => collect(BookingType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->label()])->all(),
        ]);
    }

    public function show(string $domain, Booking $booking): View
    {
        $booking->load([
            'serviceProduct:id,name,slug',
            'serviceCategory:id,name',
            'payments.histories.changer:id,name',
            'histories.changer:id,name',
            'priceHistories.changer:id,name',
        ]);

        return view('booking.show', [
            'booking' => $booking,
            'bookingStatuses' => BookingStatus::options(),
            'paymentStatuses' => PaymentStatus::options(),
            'paymentMethods' => PaymentMethod::options(),
            'bookingTypes' => collect(BookingType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->label()])->all(),
            'pricingModes' => collect(PricingMode::cases())->mapWithKeys(fn ($mode) => [$mode->value => $mode->label()])->all(),
        ]);
    }

    public function updateStatus(
        UpdateBookingStatusRequest $request,
        string $domain,
        Booking $booking,
        BookingService $bookings
    ): RedirectResponse {
        $bookings->transition(
            booking: $booking,
            toStatus: $request->validated('status'),
            note: $request->validated('note'),
            cancelReason: $request->validated('cancel_reason'),
            changedBy: $request->user()?->id,
        );

        return back()->with('success', 'Cập nhật trạng thái booking thành công.');
    }

    public function updateInternalNote(UpdateInternalNoteRequest $request, string $domain, Booking $booking): RedirectResponse
    {
        $booking->update($request->validated());

        return back()->with('success', 'Đã cập nhật ghi chú nội bộ.');
    }

    public function updatePayment(
        UpdatePaymentRequest $request,
        string $domain,
        Booking $booking,
        PaymentService $payments
    ): RedirectResponse {
        $payment = $booking->payments()->firstOrCreate([], [
            'provider' => 'manual',
            'amount' => $booking->total_amount,
            'currency' => $booking->currency,
            'status' => $booking->payment_status,
            'method' => $booking->payment_method,
        ]);

        $payments->updateManualPayment($payment, $request->validated(), $request->user()?->id);

        return back()->with('success', 'Đã cập nhật thanh toán.');
    }

    public function updateQuote(UpdateBookingQuoteRequest $request, string $domain, Booking $booking): RedirectResponse
    {
        $fromTotal = $booking->total_amount;
        $fromMode = $booking->pricing_mode instanceof PricingMode
            ? $booking->pricing_mode->value
            : (string) $booking->pricing_mode;
        $total = number_format((float) $request->validated('total_amount'), 2, '.', '');

        $booking->update([
            'pricing_mode' => PricingMode::Fixed->value,
            'unit_price' => $total,
            'subtotal' => $total,
            'discount_amount' => '0.00',
            'total_amount' => $total,
            'pricing_snapshot' => array_merge($booking->pricing_snapshot ?? [], [
                'pricing_mode' => PricingMode::Fixed->value,
                'manual_quote_total' => $total,
                'manual_quote_at' => now()->toISOString(),
            ]),
        ]);

        $booking->priceHistories()->create([
            'from_total_amount' => $fromTotal,
            'to_total_amount' => $total,
            'from_pricing_mode' => $fromMode ?: null,
            'to_pricing_mode' => PricingMode::Fixed->value,
            'changed_by' => $request->user()?->id,
            'note' => $request->validated('note'),
        ]);

        $payment = $booking->payments()->first();
        if ($payment) {
            $payment->update(['amount' => $total]);
        }

        return back()->with('success', 'Đã cập nhật báo giá booking.');
    }
}
