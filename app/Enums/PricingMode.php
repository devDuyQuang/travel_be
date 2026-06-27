<?php

namespace App\Enums;

enum PricingMode: string
{
    case Fixed = 'fixed';
    case PerPerson = 'per_person';
    case PerVehicle = 'per_vehicle';
    case PerRoom = 'per_room';
    case Quote = 'quote';

    public function label(): string
    {
        return match ($this) {
            self::Fixed => 'Giá cố định',
            self::PerPerson => 'Theo người',
            self::PerVehicle => 'Theo xe',
            self::PerRoom => 'Theo phòng',
            self::Quote => 'Cần báo giá',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
