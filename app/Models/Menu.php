<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
        'name',
        'path',
        'icon',
        'color',
        'parent_id',
        'topic',
        'part_id',
        'location',
        'status',
        'sort',
        'order_position',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'integer',
        'sort' => 'integer',
        'order_position' => 'integer',
        'parent_id' => 'integer',
        'part_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }
}
