<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Post extends Model
{
    public $table = 'posts';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'description',
        'content',
        'image',
        'views',
        'favorites',
        'created_by',
        'updated_by',
        'title_seo',
        'description_seo',
        'canonical_seo',
    ];

    protected $casts = [
        'status'     => 'integer',
        'views'      => 'integer',
        'favorites'  => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    // Quan hệ với người tạo
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Quan hệ với người cập nhật
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Nhiều-nhiều với danh mục (category_post)
    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class, 'category_post', 'post_id', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(\App\Models\Tag::class, 'post_tag', 'post_id', 'tag_id')->withTimestamps();
    }

    // Scope: chỉ lấy bài viết active (status = 1)
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Scope: sắp xếp theo tên (hoặc bạn có thể thay bằng created_at nếu muốn)
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }

    // Scope: sắp xếp theo lượt xem nhiều nhất
    public function scopeMostViewed($query)
    {
        return $query->orderByDesc('views');
    }

    // Scope: sắp xếp theo lượt yêu thích nhiều nhất
    public function scopeMostFavorited($query)
    {
        return $query->orderByDesc('favorites');
    }

    // Accessor: trả về URL ảnh đã được xử lý (nếu có helper normalize_image_url)
    public function getImageUrlAttribute(): ?string
    {
        return function_exists('normalize_image_url')
            ? normalize_image_url($this->image, 'post')
            : $this->image;
    }

    // Tăng lượt xem (dùng trong controller)
    public function incrementViews()
    {
        $this->increment('views');
    }

    // Tăng/giảm lượt yêu thích (tùy logic của bạn)
    public function incrementFavorites()
    {
        $this->increment('favorites');
    }

    public function decrementFavorites()
    {
        $this->decrement('favorites');
    }
}
