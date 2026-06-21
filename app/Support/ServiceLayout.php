<?php

namespace App\Support;

final class ServiceLayout
{
    public const TEE_TIME = 'tee_time';
    public const TOUR = 'tour';
    public const ACCOMMODATION = 'accommodation';
    public const TRANSPORT = 'transport';
    public const ATTRACTION = 'attraction';

    public static function options(): array
    {
        return [
            self::TEE_TIME => 'Đặt tee time',
            self::TOUR => 'Tour golf',
            self::ACCOMMODATION => 'Lưu trú',
            self::TRANSPORT => 'Thuê xe',
            self::ATTRACTION => 'Tham quan',
        ];
    }

    public static function keys(): array
    {
        return array_keys(self::options());
    }

    public static function categoryDefaults(): array
    {
        return [
            'dat-tee-time' => self::TEE_TIME,
            'tour-golf-viet-nam' => self::TOUR,
            'khach-san-nghi-duong' => self::ACCOMMODATION,
            'thue-xe-dua-don' => self::TRANSPORT,
            'tham-quan-trai-nghiem' => self::ATTRACTION,
        ];
    }

    public static function label(?string $key): string
    {
        return self::options()[$key] ?? 'Mặc định';
    }
}
