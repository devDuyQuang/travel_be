<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentStatus;
use App\Enums\PricingMode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'public_id',
        'booking_code',
        'idempotency_key',
        'idempotency_payload_hash',
        'customer_id',
        'service_product_id',
        'service_category_id',
        'booking_type',
        'service_name_snapshot',
        'service_slug_snapshot',
        'customer_name',
        'customer_email',
        'customer_phone',
        'start_date',
        'end_date',
        'start_time',
        'adults',
        'children',
        'quantity',
        'currency',
        'pricing_mode',
        'unit_price',
        'subtotal',
        'discount_amount',
        'total_amount',
        'pricing_snapshot',
        'booking_details',
        'customer_note',
        'internal_note',
        'booking_status',
        'payment_status',
        'payment_method',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'cancel_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'adults' => 'integer',
        'children' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'pricing_snapshot' => 'array',
        'booking_details' => 'array',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'booking_status' => BookingStatus::class,
        'booking_type' => BookingType::class,
        'payment_status' => PaymentStatus::class,
        'pricing_mode' => PricingMode::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function serviceProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'service_product_id');
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'service_category_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(BookingStatusHistory::class)->latest();
    }

    public function priceHistories(): HasMany
    {
        return $this->hasMany(BookingPriceHistory::class)->latest();
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable')->latest();
    }
}
