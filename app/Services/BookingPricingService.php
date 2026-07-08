<?php
//Phần giá  không tin từ frontend.
// Frontend chỉ hiển thị giá tạm tính cho user xem.
// Khi submit, backend tự load service option từ database,
// kiểm tra option có thuộc đúng product và còn active không, 
//rồi mới tính tổng tiền và lưu snapshot.
namespace App\Services;

use App\Enums\BookingType;
use App\Enums\PricingMode;
use App\Models\Product;
use App\Models\ServiceProductOption;
use App\Support\Money;
use Illuminate\Validation\ValidationException;

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
            $selectedOption = $this->selectedOption(
                $product,
                $details,
                $type === BookingType::Tour ? ['tour_package'] : ['ticket_type']
            );
            $guestCount = max(1, $adults + $children);

            if ($selectedOption && $selectedOption->price !== null && Money::normalize($selectedOption->price) !== '0.00') {
                $unitPrice = Money::normalize($selectedOption->price);
                $subtotal = Money::multiply($unitPrice, $guestCount);

                return [
                    'pricing_mode' => PricingMode::PerPerson,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'discount_amount' => '0.00',
                    'total_amount' => $subtotal,
                    'quantity' => $guestCount,
                    'details' => array_merge($details, [
                        'adults' => $adults,
                        'children' => $children,
                        'guests' => $guestCount,
                        ...$this->optionDetails($selectedOption, $guestCount),
                        'extras' => $extras,
                    ]),
                    'snapshot' => [
                        'pricing_mode' => PricingMode::PerPerson->value,
                        'adults' => $adults,
                        'children' => $children,
                        'guests' => $guestCount,
                        ...$this->optionSnapshot($selectedOption, $guestCount, $subtotal),
                        'currency' => 'VND',
                    ],
                ];
            }

            $adultPrice = $this->firstMoney($attributes['adult_price'] ?? null, $product->price, $product->regular_price, $product->sale_price, $product->price_discount);
            $childPrice = $this->firstMoney($attributes['child_price'] ?? null, $adultPrice);

            if ($adultPrice === null && $childPrice === null) {
                return $this->quoteSnapshot($type, $selectedOption ? array_merge($details, $this->optionDetails($selectedOption, $guestCount)) : $details, $extras, [
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
            $selectedOption = $this->selectedOption($product, $details, ['vehicle_type']);
            $vehiclePrice = $this->firstMoney($selectedOption?->price, $attributes['vehicle_price'] ?? null, $attributes['base_price'] ?? null, $product->price, $product->regular_price, $product->sale_price, $product->price_discount);
            $passengers = max(1, (int) ($details['passengers'] ?? $data['quantity'] ?? 1));

            if ($vehiclePrice === null) {
                return $this->quoteSnapshot($type, $selectedOption ? array_merge($details, $this->optionDetails($selectedOption, 1)) : $details, $extras, ['passengers' => $passengers]);
            }

            return [
                'pricing_mode' => PricingMode::PerVehicle,
                'unit_price' => $vehiclePrice,
                'subtotal' => $vehiclePrice,
                'discount_amount' => '0.00',
                'total_amount' => $vehiclePrice,
                'quantity' => 1,
                'details' => array_merge($details, ['passengers' => $passengers], $selectedOption ? $this->optionDetails($selectedOption, 1) : []),
                'snapshot' => [
                    'pricing_mode' => PricingMode::PerVehicle->value,
                    'vehicle_price' => $vehiclePrice,
                    'passengers' => $passengers,
                    ...($selectedOption ? $this->optionSnapshot($selectedOption, 1, $vehiclePrice) : []),
                    'currency' => 'VND',
                ],
            ];
        }

        if ($type === BookingType::Hotel) {
            $selectedOption = $this->selectedOption($product, $details, ['room_type']);
            $roomPrice = $this->firstMoney($selectedOption?->price, $attributes['room_price'] ?? null, $attributes['base_price'] ?? null, $product->price, $product->regular_price, $product->sale_price, $product->price_discount);
            $rooms = max(1, (int) ($details['rooms'] ?? $data['quantity'] ?? 1));
            $nights = $this->nightsBetween($data['start_date'] ?? null, $data['end_date'] ?? null);

            if ($roomPrice === null) {
                return $this->quoteSnapshot($type, $selectedOption ? array_merge($details, $this->optionDetails($selectedOption, $rooms * $nights)) : $details, $extras, [
                    'rooms' => $rooms,
                    'nights' => $nights,
                    'adults' => $adults,
                    'children' => $children,
                ]);
            }

            $subtotal = Money::multiply($roomPrice, $rooms * $nights);

            return [
                'pricing_mode' => PricingMode::PerRoom,
                'unit_price' => $roomPrice,
                'subtotal' => $subtotal,
                'discount_amount' => '0.00',
                'total_amount' => $subtotal,
                'quantity' => $rooms,
                'details' => array_merge($details, [
                    'rooms' => $rooms,
                    'nights' => $nights,
                    'adults' => $adults,
                    'children' => $children,
                    ...($selectedOption ? $this->optionDetails($selectedOption, $rooms * $nights) : []),
                    'extras' => $extras,
                ]),
                'snapshot' => [
                    'pricing_mode' => PricingMode::PerRoom->value,
                    'room_price' => $roomPrice,
                    'rooms' => $rooms,
                    'nights' => $nights,
                    ...($selectedOption ? $this->optionSnapshot($selectedOption, $rooms * $nights, $subtotal) : []),
                    'currency' => 'VND',
                ],
            ];
        }

        if ($type === BookingType::TeeTime) {
            $selectedOption = $this->selectedOption($product, $details, ['golf_package', 'time_slot']);
            $golfers = max(1, (int) ($details['golfers'] ?? $data['quantity'] ?? 1));
            $golfPrice = $this->firstMoney($selectedOption?->price, $attributes['golf_price'] ?? null, $attributes['base_price'] ?? null, $product->price, $product->regular_price, $product->sale_price, $product->price_discount);

            if ($golfPrice === null) {
                return $this->quoteSnapshot($type, $selectedOption ? array_merge($details, $this->optionDetails($selectedOption, $golfers)) : $details, $extras, ['golfers' => $golfers]);
            }

            $subtotal = Money::multiply($golfPrice, $golfers);

            return [
                'pricing_mode' => PricingMode::PerPerson,
                'unit_price' => $golfPrice,
                'subtotal' => $subtotal,
                'discount_amount' => '0.00',
                'total_amount' => $subtotal,
                'quantity' => $golfers,
                'details' => array_merge($details, [
                    'golfers' => $golfers,
                    ...($selectedOption ? $this->optionDetails($selectedOption, $golfers) : []),
                    'extras' => $extras,
                ]),
                'snapshot' => [
                    'pricing_mode' => PricingMode::PerPerson->value,
                    'golfers' => $golfers,
                    ...($selectedOption ? $this->optionSnapshot($selectedOption, $golfers, $subtotal) : []),
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

    private function selectedOption(Product $product, array $details, array $allowedTypes): ?ServiceProductOption
    {
        $optionId = $details['service_option_id'] ?? null;

        if (! $optionId) {
            return null;
        }

        $option = ServiceProductOption::query()
            ->whereKey($optionId)
            ->first();

        if (! $option || (int) $option->service_product_id !== (int) $product->id) {
            throw ValidationException::withMessages([
                'booking_details.service_option_id' => 'Tùy chọn dịch vụ không thuộc dịch vụ đang chọn.',
            ]);
        }

        if (! $option->is_active) {
            throw ValidationException::withMessages([
                'booking_details.service_option_id' => 'Tùy chọn dịch vụ hiện không khả dụng.',
            ]);
        }

        if (! in_array($option->type, $allowedTypes, true)) {
            throw ValidationException::withMessages([
                'booking_details.service_option_id' => 'Tùy chọn dịch vụ không phù hợp với loại booking.',
            ]);
        }

        return $option;
    }

    private function optionDetails(ServiceProductOption $option, int $quantityBasis): array
    {
        return [
            'option_id' => $option->id,
            'option_type' => $option->type,
            'option_name' => $option->name,
            'unit_price' => $option->price,
            'unit' => $option->unit,
            'quantity_basis' => $quantityBasis,
            'calculated_total' => $option->price !== null
                ? Money::multiply(Money::normalize($option->price), max(1, $quantityBasis))
                : '0.00',
        ];
    }

    private function optionSnapshot(ServiceProductOption $option, int $quantityBasis, string $total): array
    {
        return [
            'option_id' => $option->id,
            'option_type' => $option->type,
            'option_name' => $option->name,
            'unit_price' => $option->price,
            'unit' => $option->unit,
            'quantity_basis' => $quantityBasis,
            'calculated_total' => $total,
        ];
    }

    private function nightsBetween(mixed $startDate, mixed $endDate): int
    {
        $start = strtotime((string) $startDate);
        $end = strtotime((string) $endDate);

        if ($start === false || $end === false || $end <= $start) {
            return 1;
        }

        return max(1, (int) ceil(($end - $start) / 86400));
    }

    private function quoteSnapshot(BookingType $type, array $details, array $extras, array $meta): array
    {
        return [
            'pricing_mode' => PricingMode::Quote,
            'unit_price' => '0.00',
            'subtotal' => '0.00',
            'discount_amount' => '0.00',
            'total_amount' => '0.00',
            'quantity' => max(1, (int) ($meta['rooms'] ?? $meta['adults'] ?? $meta['passengers'] ?? 1)),
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
