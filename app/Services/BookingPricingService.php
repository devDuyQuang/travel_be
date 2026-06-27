<?php

namespace App\Services;

use App\Enums\BookingType;
use App\Enums\PricingMode;
use App\Models\Product;
use App\Support\Money;

class BookingPricingService
{
    public function calculate(Product $product, BookingType $type, array $data): array
    {
        $attributes = is_array($product->attributes) ? $product->attributes : [];
        $adults = max(0, (int) ($data['adults'] ?? 0));
        $children = max(0, (int) ($data['children'] ?? 0));
        $details = is_array($data['booking_details'] ?? null) ? $data['booking_details'] : [];
        $extras = $this->normalizeExtras($data['extras'] ?? []);

        if (in_array($type, [BookingType::Tour, BookingType::Attraction], true)) {
            $adultPrice = $this->firstMoney($attributes['adult_price'] ?? null, $product->price, $product->regular_price, $product->sale_price, $product->price_discount);
            $childPrice = $this->firstMoney($attributes['child_price'] ?? null, $adultPrice);

            if ($adultPrice === null && $childPrice === null) {
                return $this->quoteSnapshot($type, $details, $extras, [
                    'adults' => $adults,
                    'children' => $children,
                ]);
            }

            $adultSubtotal = Money::multiply($adultPrice ?? '0.00', $adults);
            $childSubtotal = Money::multiply($childPrice ?? '0.00', $children);
            $extraTotal = $this->extrasTotal($extras, $adults + $children);
            $subtotal = Money::add(Money::add($adultSubtotal, $childSubtotal), $extraTotal);

            return [
                'pricing_mode' => PricingMode::PerPerson,
                'unit_price' => $adultPrice ?? '0.00',
                'subtotal' => $subtotal,
                'discount_amount' => '0.00',
                'total_amount' => $subtotal,
                'quantity' => max(1, $adults + $children),
                'details' => array_merge($details, [
                    'adults' => $adults,
                    'children' => $children,
                    'extras' => $extras,
                ]),
                'snapshot' => [
                    'pricing_mode' => PricingMode::PerPerson->value,
                    'adult_price' => $adultPrice,
                    'child_price' => $childPrice,
                    'adults' => $adults,
                    'children' => $children,
                    'extras' => $extras,
                    'extras_total' => $extraTotal,
                    'currency' => 'VND',
                ],
            ];
        }

        if ($type === BookingType::Transport) {
            $vehiclePrice = $this->firstMoney($attributes['vehicle_price'] ?? null, $attributes['base_price'] ?? null, $product->price, $product->regular_price, $product->sale_price, $product->price_discount);
            $passengers = max(1, (int) ($details['passengers'] ?? $data['quantity'] ?? 1));

            if ($vehiclePrice === null) {
                return $this->quoteSnapshot($type, $details, $extras, ['passengers' => $passengers]);
            }

            return [
                'pricing_mode' => PricingMode::PerVehicle,
                'unit_price' => $vehiclePrice,
                'subtotal' => $vehiclePrice,
                'discount_amount' => '0.00',
                'total_amount' => $vehiclePrice,
                'quantity' => 1,
                'details' => array_merge($details, ['passengers' => $passengers]),
                'snapshot' => [
                    'pricing_mode' => PricingMode::PerVehicle->value,
                    'vehicle_price' => $vehiclePrice,
                    'passengers' => $passengers,
                    'currency' => 'VND',
                ],
            ];
        }

        return $this->quoteSnapshot($type, $details, $extras, [
            'adults' => $adults,
            'children' => $children,
        ]);
    }

    private function firstMoney(mixed ...$values): ?string
    {
        foreach ($values as $value) {
            if ($value !== null && $value !== '' && Money::normalize($value) !== '0.00') {
                return Money::normalize($value);
            }
        }

        return null;
    }

    private function normalizeExtras(mixed $extras): array
    {
        if (! is_array($extras)) {
            return [];
        }

        return collect($extras)
            ->filter(fn ($extra) => is_array($extra) && filled($extra['name'] ?? null))
            ->map(fn ($extra) => [
                'name' => trim((string) $extra['name']),
                'pricing_type' => ($extra['pricing_type'] ?? 'per_booking') === 'per_person' ? 'per_person' : 'per_booking',
                'price' => Money::normalize($extra['price'] ?? 0),
            ])
            ->values()
            ->all();
    }

    private function extrasTotal(array $extras, int $people): string
    {
        $total = '0.00';

        foreach ($extras as $extra) {
            $line = $extra['pricing_type'] === 'per_person'
                ? Money::multiply($extra['price'], max(0, $people))
                : $extra['price'];
            $total = Money::add($total, $line);
        }

        return $total;
    }

    private function quoteSnapshot(BookingType $type, array $details, array $extras, array $meta): array
    {
        return [
            'pricing_mode' => PricingMode::Quote,
            'unit_price' => '0.00',
            'subtotal' => '0.00',
            'discount_amount' => '0.00',
            'total_amount' => '0.00',
            'quantity' => max(1, (int) ($meta['adults'] ?? $meta['passengers'] ?? 1)),
            'details' => array_merge($details, $meta, ['extras' => $extras]),
            'snapshot' => [
                'pricing_mode' => PricingMode::Quote->value,
                'booking_type' => $type->value,
                'currency' => 'VND',
                'note' => 'Dịch vụ cần báo giá thủ công.',
            ],
        ];
    }
}
