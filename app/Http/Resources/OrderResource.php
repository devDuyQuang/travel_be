<?php

namespace App\Http\Resources;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $orderStatus = $this->order_status instanceof OrderStatus
            ? $this->order_status
            : OrderStatus::tryFrom((string) $this->order_status);
        $paymentStatus = $this->payment_status instanceof PaymentStatus
            ? $this->payment_status
            : PaymentStatus::tryFrom((string) $this->payment_status);

        return [
            'public_id' => (string) $this->public_id,
            'order_code' => $this->order_code,
            'customer' => [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'phone' => $this->customer_phone,
            ],
            'shipping' => [
                'address_line' => $this->shipping_address_line,
                'ward' => $this->shipping_ward,
                'district' => $this->shipping_district,
                'province' => $this->shipping_province,
                'country' => $this->shipping_country,
            ],
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($item) => [
                'product_id' => $item->product_id,
                'name' => $item->product_name_snapshot,
                'slug' => $item->product_slug_snapshot,
                'sku' => $item->sku_snapshot,
                'image' => $item->image_snapshot,
                'unit_price' => $item->unit_price,
                'quantity' => (int) $item->quantity,
                'line_total' => $item->line_total,
            ])->values()),
            'amounts' => [
                'currency' => $this->currency,
                'subtotal' => $this->subtotal,
                'discount_amount' => $this->discount_amount,
                'shipping_fee' => $this->shipping_fee,
                'total_amount' => $this->total_amount,
            ],
            'order_status' => [
                'value' => $orderStatus?->value,
                'label' => $orderStatus?->label(),
            ],
            'payment_status' => [
                'value' => $paymentStatus?->value,
                'label' => $paymentStatus?->label(),
            ],
            'payment_method' => $this->payment_method,
            'created_at' => optional($this->created_at)->toISOString(),
        ];
    }
}
