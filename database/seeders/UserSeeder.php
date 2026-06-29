<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('code', 'admin')->first();

        User::updateOrCreate(
            ['email' => env('SEED_ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('SEED_ADMIN_NAME', 'Admin'),
                'password' => env('SEED_ADMIN_PASSWORD', 'secret12345'),
                'email_verified_at' => now(),
                'role_id' => $adminRole?->id,
            ]
        );
    }
}
