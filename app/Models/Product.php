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
        'product_type',
        'sku',

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
        'attributes',
        'content',
        'price',
        'price_discount',
        'regular_price',
        'sale_price',
        'stock_quantity',
        'manage_stock',
        'stock_status',
        'status',
        'is_featured',
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
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'manage_stock' => 'boolean',
        'review_rating' => 'decimal:1',
        'established_year' => 'integer',
        'status' => 'integer',
        'is_featured' => 'boolean',
        'attributes' => 'array',
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

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'service_product_id');
    }

    public function serviceOptions(): HasMany
    {
        return $this->hasMany(ServiceProductOption::class, 'service_product_id')->orderBy('sort_order')->orderBy('id');
    }

    public function activeServiceOptions(): HasMany
    {
        return $this->serviceOptions()->where('is_active', true);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
