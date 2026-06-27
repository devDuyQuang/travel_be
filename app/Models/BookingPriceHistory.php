<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPriceHistory extends Model
{
    protected $fillable = [
        'booking_id',
        'from_total_amount',
        'to_total_amount',
        'from_pricing_mode',
        'to_pricing_mode',
        'changed_by',
        'note',
    ];

    protected $casts = [
        'from_total_amount' => 'decimal:2',
        'to_total_amount' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
