<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permission',
            'role_id',
            'permission_id'
        );
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission(string $permissionCode): bool
    {
        return $this->permissions()
            ->where('permissions.code', $permissionCode)
            ->where('permissions.status', 1)
            ->exists();
    }
}
