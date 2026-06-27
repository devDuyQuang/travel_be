<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        $legacySettings = DB::table('settings')
            ->where('key', 'about_page_vision_mission')
            ->orWhere('key', 'like', 'about_page_vision_mission_%')
            ->get();

        foreach ($legacySettings as $legacySetting) {
            $legacyValue = is_string($legacySetting->value)
                ? json_decode($legacySetting->value, true)
                : (array) $legacySetting->value;

            if (!is_array($legacyValue)) {
                $legacyValue = [];
            }

            $suffix = substr($legacySetting->key, strlen('about_page_vision_mission'));
            $introKey = 'about_page_intro' . $suffix;
            $valuesKey = 'about_page_values' . $suffix;

            $this->insertWhenMissingOrEmpty($introKey, [
                'subtitle' => $legacyValue['subtitle'] ?? '',
                'title' => $legacyValue['title'] ?? '',
                'description' => $legacyValue['description'] ?? '',
                'button_text' => $legacyValue['button_text'] ?? '',
                'button_link' => $legacyValue['button_link'] ?? '',
                'image' => $legacyValue['image'] ?? '',
            ]);

            $this->insertWhenMissingOrEmpty($valuesKey, [
                'choose_subtitle' => $legacyValue['choose_subtitle'] ?? '',
                'choose_title' => $legacyValue['choose_title'] ?? '',
                'choose_description' => $legacyValue['choose_description'] ?? '',
                'items' => is_array($legacyValue['items'] ?? null) ? $legacyValue['items'] : [],
            ]);
        }

        DB::table('settings')
            ->where('key', 'about_page_vision_mission')
            ->orWhere('key', 'like', 'about_page_vision_mission_%')
            ->delete();
    }

    public function down(): void
    {
        // Dữ liệu đã được tách theo đúng trách nhiệm; không gộp ngược để tránh mất thay đổi mới.
    }

    private function insertWhenMissingOrEmpty(string $key, array $value): void
    {
        $existing = DB::table('settings')->where('key', $key)->first();

        if ($existing) {
            $existingValue = is_string($existing->value)
                ? json_decode($existing->value, true)
                : (array) $existing->value;

            if (!empty($existingValue)) {
                return;
            }

            DB::table('settings')->where('key', $key)->update([
                'value' => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);

            return;
        }

        DB::table('settings')->insert([
            'key' => $key,
            'value' => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
