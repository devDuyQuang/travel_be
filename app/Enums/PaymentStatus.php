<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case Pending = 'pending';
    case Paid = 'paid';
    case PartiallyRefunded = 'partially_refunded';
    case Refunded = 'refunded';
    case Failed = 'failed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'Chưa thanh toán',
            self::Pending => 'Chờ thanh toán',
            self::Paid => 'Đã thanh toán',
            self::PartiallyRefunded => 'Hoàn tiền một phần',
            self::Refunded => 'Đã hoàn tiền',
            self::Failed => 'Thanh toán lỗi',
            self::Cancelled => 'Đã hủy',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Unpaid => 'bg-label-secondary',
            self::Pending => 'bg-label-warning',
            self::Paid => 'bg-label-success',
            self::PartiallyRefunded => 'bg-label-info',
            self::Refunded => 'bg-label-info',
            self::Failed => 'bg-label-danger',
            self::Cancelled => 'bg-label-danger',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function frontendCreatableValues(): array
    {
        return [
            self::Unpaid->value,
            self::Pending->value,
        ];
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $status) => [
            $status->value => $status->label(),
        ])->all();
    }
}
