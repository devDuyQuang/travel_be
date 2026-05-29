<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

    protected $fillable = ['name', 'email', 'phone', 'service', 'message', 'status'];
    // Trạng thái hiển thị
    public function getStatusTextAttribute() {
        return [
            0 => 'Chờ xử lý',
            1 => 'Đã xác nhận',
            2 => 'Đã hủy'
        ][$this->status] ?? 'Không xác định';
    }
}
