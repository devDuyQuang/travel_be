<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;
use App\Models\User;
use App\Models\Post;

class Category extends Model
{
    public $table = 'categories';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'home',
        'description',
        'content',
        'image',
        'icon',
        'type',
        'layout_key',
        'sort',
        'order_position',
        'parent_id',
        'created_by',
        'updated_by',
        'title_seo',
        'description_seo',
        'canonical_seo',
    ];

    protected $casts = [
        'status'         => 'integer',
        'sort'           => 'integer',
        'order_position' => 'integer',
        'parent_id'      => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'home' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort');
    }

    public function stories()
    {
        return $this->belongsToMany(
            Post::class,
            'category_post',
            'category_id',
            'post_id'
        )->withTimestamps();
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'category_post', 'category_id', 'post_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('name');
    }

    public function getImageUrlAttribute(): ?string
    {
        return function_exists('normalize_image_url')
            ? normalize_image_url($this->image, 'category')
            : $this->image;
    }

    public function getFullSlugAttribute(): string
    {
        $slugs = [];
        $category = $this;

        while ($category) {
            $slugs[] = $category->slug;
            $category = $category->parent;
        }

        return implode('/', array_reverse($slugs));
    }
}
