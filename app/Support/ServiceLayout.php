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
            'golf' => self::TEE_TIME,
            'dat-tee-time' => self::TEE_TIME,
            'tour-trai-nghiem' => self::TOUR,
            'tour-golf-viet-nam' => self::TOUR,
            'khach-san' => self::ACCOMMODATION,
            'khach-san-nghi-duong' => self::ACCOMMODATION,
            'thue-xe' => self::TRANSPORT,
            'thue-xe-dua-don' => self::TRANSPORT,
            've-tham-quan' => self::ATTRACTION,
            'tham-quan-trai-nghiem' => self::ATTRACTION,
        ];
    }

    public static function label(?string $key): string
    {
        return self::options()[$key] ?? 'Mặc định';
    }
}
