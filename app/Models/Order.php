<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'public_id',
        'order_code',
        'idempotency_key',
        'idempotency_payload_hash',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address_line',
        'shipping_ward',
        'shipping_district',
        'shipping_province',
        'shipping_country',
        'currency',
        'subtotal',
        'discount_amount',
        'shipping_fee',
        'total_amount',
        'customer_note',
        'internal_note',
        'order_status',
        'payment_status',
        'payment_method',
        'confirmed_at',
        'shipped_at',
        'completed_at',
        'cancelled_at',
        'cancel_reason',
        'stock_deducted_at',
        'stock_restored_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'stock_deducted_at' => 'datetime',
        'stock_restored_at' => 'datetime',
        'order_status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable')->latest();
    }
}
