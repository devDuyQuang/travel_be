<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRegistration extends Model
{
    use HasFactory;

    // Cho phép gán hàng loạt các trường này
    protected $fillable = [
        'package_name',
        'package_price',
        'full_name',
        'email',
        'phone',
        'message',
        'status',
        'admin_note'
    ];

    // Định nghĩa các hằng số trạng thái để dễ dùng trong code
    const STATUS_PENDING = 'pending';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_CANCELED = 'canceled';
}
