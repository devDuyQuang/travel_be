<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::updateOrCreate(
            ['code' => 'admin'],
            [
                'name' => 'Admin',
                'description' => 'Quản trị hệ thống',
                'status' => 1,
            ]
        );

        $permissions = [
            ['code' => 'post.create', 'name' => 'Tạo bài viết'],
            ['code' => 'post.delete', 'name' => 'Xóa bài viết'],
            ['code' => 'post.update', 'name' => 'Sửa bài viết'],
            ['code' => 'post.view',   'name' => 'Xem bài viết'],

            ['code' => 'user.create', 'name' => 'Tạo thành viên'],
            ['code' => 'user.delete', 'name' => 'Xóa thành viên'],
            ['code' => 'user.update', 'name' => 'Sửa thành viên'],
            ['code' => 'user.view',   'name' => 'Xem thành viên'],

            ['code' => 'role.create', 'name' => 'Tạo vai trò'],
            ['code' => 'role.delete', 'name' => 'Xóa vai trò'],
            ['code' => 'role.update', 'name' => 'Sửa vai trò'],
            ['code' => 'role.view',   'name' => 'Xem vai trò'],
        ];

        $permissionIds = [];

        foreach ($permissions as $permission) {
            $item = Permission::updateOrCreate(
                ['code' => $permission['code']],
                [
                    'name' => $permission['name'],
                    'status' => 1,
                ]
            );

            $permissionIds[] = $item->id;
        }

        $adminRole->permissions()->syncWithoutDetaching($permissionIds);
    }
}
