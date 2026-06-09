<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'image',
        'description',
        'location',
        'duration',
        'review_rating',
        'review_count',
        'star_rating',
        'established_year',
        'highlight',
        'facility',
        'content',
        'price',
        'price_discount',
        'status',
        'golf_information',
        'badge_text',
        // ...
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_discount' => 'decimal:2',
        'review_rating' => 'decimal:1',
        'established_year' => 'integer',
        'status' => 'integer',
        'sort' => 'integer',
        'order_position' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }
}
