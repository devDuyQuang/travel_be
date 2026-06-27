<?php

namespace App\Support;

class Money
{
    public static function normalize(mixed $amount): string
    {
        return self::formatMinor(self::toMinor($amount));
    }

    public static function multiply(mixed $amount, int $quantity): string
    {
        return self::formatMinor(self::toMinor($amount) * max(0, $quantity));
    }

    public static function add(mixed $left, mixed $right): string
    {
        return self::formatMinor(self::toMinor($left) + self::toMinor($right));
    }

    public static function subtract(mixed $left, mixed $right): string
    {
        return self::formatMinor(max(0, self::toMinor($left) - self::toMinor($right)));
    }

    private static function toMinor(mixed $amount): int
    {
        $value = trim((string) ($amount ?? '0'));
        $value = str_replace(',', '.', $value);
        $negative = str_starts_with($value, '-');
        $value = ltrim($value, '+-');
        [$whole, $fraction] = array_pad(explode('.', $value, 2), 2, '');
        $whole = preg_replace('/\D/', '', $whole) ?: '0';
        $fraction = substr(str_pad(preg_replace('/\D/', '', $fraction) ?: '', 2, '0'), 0, 2);
        $minor = ((int) $whole * 100) + (int) $fraction;

        return $negative ? -$minor : $minor;
    }

    private static function formatMinor(int $minor): string
    {
        $sign = $minor < 0 ? '-' : '';
        $minor = abs($minor);

        return sprintf('%s%d.%02d', $sign, intdiv($minor, 100), $minor % 100);
    }
}
