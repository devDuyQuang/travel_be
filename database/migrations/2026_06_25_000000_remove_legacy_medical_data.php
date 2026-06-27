<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('degrees');

        if (Schema::hasTable('domains') && Schema::hasColumn('domains', 'type')) {
            DB::table('domains')->update(['type' => 'travel']);
        }

        if (!Schema::hasTable('settings')) {
            return;
        }

        $legacyMedicalPrefixes = [
            'appointment',
            'doctor',
            'patient',
            'specialist',
            'treatment',
            'facility',
        ];

        DB::table('settings')
            ->where(function ($query) use ($legacyMedicalPrefixes) {
                foreach ($legacyMedicalPrefixes as $prefix) {
                    $query->orWhere('key', 'like', $prefix . '%');
                }
            })
            ->delete();

        $legacyRows = DB::table('settings')
            ->where(function ($query) {
                $query
                    ->where('key', 'like', '%\\_clinic')
                    ->orWhere('key', 'like', '%\\_rac')
                    ->orWhere('key', 'like', '%\\_golfnity');
            })
            ->orderBy('id')
            ->get();

        foreach ($legacyRows as $row) {
            $baseKey = Str::beforeLast(
                Str::beforeLast(
                    Str::beforeLast($row->key, '_clinic'),
                    '_rac'
                ),
                '_golfnity'
            );

            if ($baseKey === $row->key || $baseKey === '') {
                continue;
            }

            DB::table('settings')->updateOrInsert(
                ['key' => $baseKey . '_travel'],
                [
                    'value' => $row->value,
                    'updated_at' => now(),
                    'created_at' => $row->created_at ?? now(),
                ]
            );
        }

        DB::table('settings')
            ->where('key', 'like', '%\\_clinic')
            ->orWhere('key', 'like', '%\\_rac')
            ->orWhere('key', 'like', '%\\_golfnity')
            ->delete();
    }

    public function down(): void
    {
        // Legacy medical tables and data are intentionally not recreated.
    }
};
