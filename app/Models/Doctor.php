<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'specialty',
        'birth_year',
        'phone',
        'linkedin',
        'facebook',
        'twitter',
        'youtube',
        'user_id',
    ];

    protected $casts = [
        'birth_year' => 'integer',
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
