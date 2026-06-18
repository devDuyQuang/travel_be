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
        'badge_text',
        'category_id',

        'image',
        'image_original_name',
        'gallery_image_1',
        'gallery_image_1_original_name',
        'gallery_image_2',
        'gallery_image_2_original_name',
        'gallery_image_3',
        'video_url',

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

        'title_seo',
        'canonical_url',
        'description_seo',
        'established_text',
        'sort',
        'order_position',
        'created_by',
        'updated_by',
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
