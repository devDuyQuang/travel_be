<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Xem thành viên', 'code' => 'user.view'],
            ['name' => 'Tạo thành viên', 'code' => 'user.create'],
            ['name' => 'Sửa thành viên', 'code' => 'user.update'],
            ['name' => 'Xoá thành viên', 'code' => 'user.delete'],

            ['name' => 'Xem bài viết', 'code' => 'post.view'],
            ['name' => 'Tạo bài viết', 'code' => 'post.create'],
            ['name' => 'Sửa bài viết', 'code' => 'post.update'],
            ['name' => 'Xoá bài viết', 'code' => 'post.delete'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['code' => $permission['code']],
                [
                    'name' => $permission['name'],
                    'status' => 1,
                ]
            );
        }

        $admin = Role::where('code', 'admin')->first();

        if ($admin) {
            $admin->permissions()->sync(
                Permission::pluck('id')->toArray()
            );
        }
    }
}
