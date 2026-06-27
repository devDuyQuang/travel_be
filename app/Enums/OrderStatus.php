<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Preparing = 'preparing';
    case Shipping = 'shipping';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Chờ xác nhận',
            self::Confirmed => 'Đã xác nhận',
            self::Preparing => 'Đang chuẩn bị',
            self::Shipping => 'Đang giao',
            self::Completed => 'Hoàn thành',
            self::Cancelled => 'Đã hủy',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-label-warning',
            self::Confirmed => 'bg-label-info',
            self::Preparing => 'bg-label-primary',
            self::Shipping => 'bg-label-primary',
            self::Completed => 'bg-label-success',
            self::Cancelled => 'bg-label-danger',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $status) => [
            $status->value => $status->label(),
        ])->all();
    }

    public static function allowedTransitions(string $from): array
    {
        return match ($from) {
            self::Pending->value => [self::Confirmed->value, self::Cancelled->value],
            self::Confirmed->value => [self::Preparing->value, self::Cancelled->value],
            self::Preparing->value => [self::Shipping->value, self::Cancelled->value],
            self::Shipping->value => [self::Completed->value],
            default => [],
        };
    }
}
