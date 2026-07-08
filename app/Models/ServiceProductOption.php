<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceProductOption extends Model
{
    public const TYPES = [
        'room_type',
        'vehicle_type',
        'tour_package',
        'ticket_type',
        'golf_package',
        'time_slot',
        'consultation_scope',
    ];

    public const UNITS = [
        'đêm',
        'khách',
        'chuyến',
        'vé',
        'golfer',
        'ngày',
        'giờ',
        'gói',
    ];

    protected $fillable = [
        'service_product_id',
        'type',
        'name',
        'description',
        'price',
        'currency',
        'unit',
        'capacity',
        'sort_order',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'service_product_id');
    }
}
