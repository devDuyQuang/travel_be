<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use App\Support\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(private readonly PaymentService $payments)
    {
    }

    public function create(array $data, ?int $customerId = null): Order
    {
        return DB::transaction(function () use ($data, $customerId) {
            $payloadHash = $this->payloadHash($data);

            if (! empty($data['idempotency_key'])) {
                $existing = Order::query()
                    ->where('idempotency_key', $data['idempotency_key'])
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    if ($existing->idempotency_payload_hash && $existing->idempotency_payload_hash !== $payloadHash) {
                        throw ValidationException::withMessages([
                            'idempotency_key' => 'Yêu cầu đã được gửi trước đó với dữ liệu khác. Vui lòng tải lại trang và thử lại.',
                        ]);
                    }

                    return $existing->load(['items', 'payments', 'histories']);
                }
            }

            $normalizedItems = collect($data['items'])
                ->groupBy('product_id')
                ->map(fn ($rows, $productId) => [
                    'product_id' => (int) $productId,
                    'quantity' => $rows->sum(fn ($row) => (int) $row['quantity']),
                ])
                ->values();

            $products = Product::query()
                ->whereIn('id', $normalizedItems->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = '0.00';
            $lines = [];

            foreach ($normalizedItems as $item) {
                $product = $products->get($item['product_id']);
                $quantity = (int) $item['quantity'];

                if (! $product) {
                    throw ValidationException::withMessages(['items' => 'Sản phẩm không tồn tại.']);
                }

                if ((int) $product->status !== 1 || $product->product_type !== 'physical') {
                    throw ValidationException::withMessages([
                        'items' => "{$product->name} không phải sản phẩm vật lý có thể đặt hàng.",
                    ]);
                }

                if ($product->stock_status === 'out_of_stock') {
                    throw ValidationException::withMessages([
                        'items' => "{$product->name} đang tạm hết hàng.",
                    ]);
                }

                if ($product->manage_stock && (int) $product->stock_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "{$product->name} không đủ tồn kho.",
                    ]);
                }

                $unitPrice = $this->productPrice($product);
                $lineTotal = Money::multiply($unitPrice, $quantity);
                $subtotal = Money::add($subtotal, $lineTotal);

                $lines[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $discount = '0.00';
            $shippingFee = '0.00';
            $total = Money::add(Money::subtract($subtotal, $discount), $shippingFee);
            $paymentStatus = ! empty($data['payment_method'])
                ? PaymentStatus::Pending->value
                : PaymentStatus::Unpaid->value;

            $order = Order::create([
                'public_id' => (string) Str::ulid(),
                'order_code' => $this->generateCode(),
                'idempotency_key' => $data['idempotency_key'] ?? null,
                'idempotency_payload_hash' => $payloadHash,
                'customer_id' => $customerId,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address_line' => $data['shipping_address_line'],
                'shipping_ward' => $data['shipping_ward'] ?? null,
                'shipping_district' => $data['shipping_district'] ?? null,
                'shipping_province' => $data['shipping_province'],
                'shipping_country' => 'VN',
                'currency' => 'VND',
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_fee' => $shippingFee,
                'total_amount' => $total,
                'customer_note' => $data['customer_note'] ?? null,
                'order_status' => OrderStatus::Pending->value,
                'payment_status' => $paymentStatus,
                'payment_method' => $data['payment_method'] ?? null,
                'stock_deducted_at' => now(),
            ]);

            foreach ($lines as $line) {
                /** @var Product $product */
                $product = $line['product'];
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name_snapshot' => $product->name,
                    'product_slug_snapshot' => $product->slug,
                    'sku_snapshot' => $product->sku,
                    'image_snapshot' => $product->image,
                    'unit_price' => $line['unit_price'],
                    'quantity' => $line['quantity'],
                    'line_total' => $line['line_total'],
                ]);

                // Stock is deducted immediately after an order is created.
                // Cancellation restores it only when stock_restored_at is still null.
                if ($product->manage_stock) {
                    $newQuantity = max(0, (int) $product->stock_quantity - (int) $line['quantity']);
                    $product->forceFill([
                        'stock_quantity' => $newQuantity,
                        'stock_status' => $newQuantity === 0 ? 'out_of_stock' : $product->stock_status,
                    ])->save();
                }
            }

            $order->histories()->create([
                'from_status' => null,
                'to_status' => OrderStatus::Pending->value,
                'changed_by' => $customerId,
                'note' => 'Khách tạo đơn hàng.',
            ]);

            $this->payments->createInitialPayment($order, [
                'amount' => $total,
                'currency' => 'VND',
                'payment_method' => $data['payment_method'] ?? null,
            ]);

            return $order->load(['items', 'payments', 'histories']);
        });
    }

    public function transition(Order $order, string $toStatus, ?string $note = null, ?string $cancelReason = null, ?int $changedBy = null): Order
    {
        return DB::transaction(function () use ($order, $toStatus, $note, $cancelReason, $changedBy) {
            $order = Order::query()->with('items.product')->whereKey($order->id)->lockForUpdate()->firstOrFail();
            $from = $order->order_status instanceof OrderStatus
                ? $order->order_status->value
                : (string) $order->order_status;

            if (! in_array($toStatus, OrderStatus::allowedTransitions($from), true)) {
                throw ValidationException::withMessages([
                    'status' => 'Không thể chuyển trạng thái đơn hàng theo thao tác này.',
                ]);
            }

            $updates = ['order_status' => $toStatus];

            if ($toStatus === OrderStatus::Confirmed->value) {
                $updates['confirmed_at'] = now();
            } elseif ($toStatus === OrderStatus::Shipping->value) {
                $updates['shipped_at'] = now();
            } elseif ($toStatus === OrderStatus::Completed->value) {
                $updates['completed_at'] = now();
            } elseif ($toStatus === OrderStatus::Cancelled->value) {
                $updates['cancelled_at'] = now();
                $updates['cancel_reason'] = $cancelReason;
            }

            $order->update($updates);

            if ($toStatus === OrderStatus::Cancelled->value) {
                $this->restoreStockOnce($order);
            }

            $order->histories()->create([
                'from_status' => $from,
                'to_status' => $toStatus,
                'changed_by' => $changedBy,
                'note' => $toStatus === OrderStatus::Cancelled->value ? $cancelReason : $note,
            ]);

            return $order->fresh(['items', 'histories', 'payments']);
        });
    }

    private function restoreStockOnce(Order $order): void
    {
        if (! $order->stock_deducted_at || $order->stock_restored_at) {
            return;
        }

        foreach ($order->items as $item) {
            if (! $item->product_id) {
                continue;
            }

            $product = Product::query()->whereKey($item->product_id)->lockForUpdate()->first();
            if (! $product || ! $product->manage_stock) {
                continue;
            }

            $newQuantity = (int) $product->stock_quantity + (int) $item->quantity;
            $product->forceFill([
                'stock_quantity' => $newQuantity,
                'stock_status' => $newQuantity > 0 ? 'in_stock' : $product->stock_status,
            ])->save();
        }

        $order->forceFill(['stock_restored_at' => now()])->save();
    }

    private function productPrice(Product $product): string
    {
        $price = $product->sale_price
            ?? $product->regular_price
            ?? $product->price_discount
            ?? $product->price
            ?? 0;

        return Money::normalize($price);
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
            $code = 'OD' . now()->format('ymd') . strtoupper(Str::random(6));
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }
}
