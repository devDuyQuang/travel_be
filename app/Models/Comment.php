<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    public $table = 'comments';

    protected $fillable = [
        'content',
        'name',
        'email',
        'phone',
        'status',
        'commentable_id',
        'commentable_type',
        'parent_id',
        'approved_by',
        'approved_at',
        'ip_address',
        'user_agent',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'status'      => 'string',
    ];

    protected $appends = ['is_admin_reply','formatted_date'];
    public function getIsAdminReplyAttribute()
    {
        // Nếu bình luận có approved_by (người duyệt) và name là Admin
        // hoặc đơn giản là dựa trên logic phân quyền của anh
        return $this->approved_by !== null && $this->name === 'Admin';
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : '';
    }

    /**
     * Các trạng thái bình luận
     */
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_HIDDEN   = 'hidden';

    /**
     * Quan hệ Polymorphic: Bình luận thuộc về model nào (Post, Service, Product...)
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Bình luận cha (nếu là reply)
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Các bình luận con (replies)
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Người duyệt bình luận (Admin/User)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope: Chỉ lấy bình luận đã duyệt
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope: Chỉ lấy bình luận chờ duyệt
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Chỉ lấy bình luận gốc (không phải reply)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Kiểm tra bình luận có phải là reply không
     */
    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Đánh dấu bình luận là đã duyệt
     */
    public function markAsApproved($approvedBy = null)
    {
        $this->update([
            'status'       => self::STATUS_APPROVED,
            'approved_by'  => $approvedBy ?? auth()->id(),
            'approved_at'  => now(),
        ]);
    }

    /**
     * Đánh dấu bình luận là ẩn/spam
     */
    public function markAsHidden()
    {
        $this->update(['status' => self::STATUS_HIDDEN]);
    }

    /**
     * Lấy tên hiển thị (nếu có tên thì dùng tên, không thì "Khách")
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: 'Khách';
    }

    /**
     * Lấy nội dung rút gọn (dùng trong bảng admin)
     */
    public function getShortContentAttribute(): string
    {
        $content = strip_tags($this->content);
        return \Str::limit($content, 100);
    }
}
