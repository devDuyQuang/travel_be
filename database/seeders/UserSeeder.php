<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('SEED_ADMIN_EMAIL', 'admin@example.com')],
            [
                'name'              => env('SEED_ADMIN_NAME', 'Admin'),
                'password'          => env('SEED_ADMIN_PASSWORD', 'secret12345'),
                'email_verified_at' => now(),
            ]
        );
    }
}
