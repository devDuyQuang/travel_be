<?php

namespace App\Http\Resources;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentStatus;
use App\Enums\PricingMode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $bookingStatus = $this->booking_status instanceof BookingStatus
            ? $this->booking_status
            : BookingStatus::tryFrom((string) $this->booking_status);
        $paymentStatus = $this->payment_status instanceof PaymentStatus
            ? $this->payment_status
            : PaymentStatus::tryFrom((string) $this->payment_status);
        $bookingType = $this->booking_type instanceof BookingType
            ? $this->booking_type
            : BookingType::tryFrom((string) $this->booking_type);
        $pricingMode = $this->pricing_mode instanceof PricingMode
            ? $this->pricing_mode
            : PricingMode::tryFrom((string) $this->pricing_mode);

        return [
            'public_id' => (string) $this->public_id,
            'booking_code' => $this->booking_code,
            'service' => [
                'id' => $this->service_product_id,
                'name' => $this->service_name_snapshot,
                'slug' => $this->service_slug_snapshot,
            ],
            'booking_type' => [
                'value' => $bookingType?->value,
                'label' => $bookingType?->label(),
            ],
            'customer' => [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'phone' => $this->customer_phone,
            ],
            'schedule' => [
                'start_date' => optional($this->start_date)->toDateString(),
                'end_date' => optional($this->end_date)->toDateString(),
                'start_time' => $this->start_time,
                'adults' => (int) $this->adults,
                'children' => (int) $this->children,
                'quantity' => (int) $this->quantity,
            ],
            'amounts' => [
                'currency' => $this->currency,
                'pricing_mode' => [
                    'value' => $pricingMode?->value,
                    'label' => $pricingMode?->label(),
                ],
                'unit_price' => $this->unit_price,
                'subtotal' => $this->subtotal,
                'discount_amount' => $this->discount_amount,
                'total_amount' => $this->total_amount,
            ],
            'booking_details' => $this->booking_details ?? [],
            'pricing_snapshot' => $this->pricing_snapshot ?? [],
            'booking_status' => [
                'value' => $bookingStatus?->value,
                'label' => $bookingStatus?->label(),
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
