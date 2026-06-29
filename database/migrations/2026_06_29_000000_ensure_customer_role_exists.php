<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $existing = DB::table('roles')->where('code', 'customer')->first();

        if ($existing) {
            DB::table('roles')->where('code', 'customer')->update([
                'name' => 'Customer',
                'description' => 'Khách hàng đặt dịch vụ',
                'status' => 1,
                'updated_at' => now(),
            ]);

            return;
        }

        DB::table('roles')->insert([
            'code' => 'customer',
            'name' => 'Customer',
            'description' => 'Khách hàng đặt dịch vụ',
            'status' => 1,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('roles')
            ->where('code', 'customer')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('users')
                    ->whereColumn('users.role_id', 'roles.id');
            })
            ->delete();
    }
};
