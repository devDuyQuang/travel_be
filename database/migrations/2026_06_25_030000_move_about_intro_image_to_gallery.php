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

        $introSettings = DB::table('settings')
            ->where('key', 'about_page_intro')
            ->orWhere('key', 'like', 'about_page_intro_%')
            ->get();

        foreach ($introSettings as $introSetting) {
            $intro = $this->decode($introSetting->value);
            $image = trim((string) ($intro['image'] ?? ''));

            if ($image !== '') {
                $suffix = substr($introSetting->key, strlen('about_page_intro'));
                $galleryKey = 'about_page_gallery' . $suffix;
                $gallerySetting = DB::table('settings')->where('key', $galleryKey)->first();
                $gallery = $this->decode($gallerySetting?->value);
                $images = is_array($gallery['images'] ?? null) ? array_values($gallery['images']) : [];

                if (count($images) < 3 && !in_array($image, $images, true)) {
                    $images[] = $image;
                    DB::table('settings')->updateOrInsert(
                        ['key' => $galleryKey],
                        [
                            'value' => json_encode(['images' => $images], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                            'updated_at' => now(),
                            'created_at' => $gallerySetting?->created_at ?? now(),
                        ]
                    );
                }
            }

            unset($intro['image']);
            DB::table('settings')->where('key', $introSetting->key)->update([
                'value' => json_encode($intro, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Ảnh đã thuộc về thư viện nên không chuyển ngược về phần nội dung.
    }

    private function decode(mixed $value): array
    {
        $decoded = is_string($value) ? json_decode($value, true) : (array) $value;

        return is_array($decoded) ? $decoded : [];
    }
};
