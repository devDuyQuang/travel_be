<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const KEYS = [
        'hero_home',
        'search_home',
        'about_home',
        'featured_products_home',
        'why_choose_us_home',
        'promo_home',
        'destinations_home',
        'cta_home',
        'testimonials_home',
        'blogs_home',
        'app_cta_home',
    ];

    public function up(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        foreach (self::KEYS as $baseKey) {
            $base = DB::table('settings')->where('key', $baseKey)->first();
            $typed = DB::table('settings')->where('key', $baseKey . '_travel')->first();

            if (!$typed) {
                continue;
            }

            $baseValue = $this->decode($base?->value);
            $typedValue = $this->decode($typed->value);

            if (empty($baseValue) && !empty($typedValue)) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $baseKey],
                    [
                        'value' => json_encode($typedValue, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'created_at' => $base?->created_at ?? now(),
                        'updated_at' => now(),
                    ]
                );
            }

            DB::table('settings')->where('key', $baseKey . '_travel')->delete();
        }
    }

    public function down(): void
    {
        // Homepage settings now use one canonical key per section.
    }

    private function decode(mixed $value): array
    {
        $decoded = is_string($value) ? json_decode($value, true) : (array) $value;

        return is_array($decoded) ? $decoded : [];
    }
};
