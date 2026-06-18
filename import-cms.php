<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

$items = json_decode(file_get_contents(__DIR__ . '/cms-import-selected.json'), true);

DB::transaction(function () use ($items) {
    foreach ($items as $item) {
        if (($item['targetType'] ?? '') === 'category') {
            $category = Category::where('slug', $item['targetSlug'])->first();

            if (! $category) {
                echo "Không tìm thấy category: {$item['targetSlug']}\n";
                continue;
            }

            $category->update([
                'name' => $item['targetName'],
                'description' => $item['description'] ?? null,
                'content' => $item['content_text'] ?? null,
                'title_seo' => $item['meta_title'] ?? null,
                'description_seo' => $item['meta_description'] ?? null,
            ]);

            echo "Updated category: {$category->name}\n";
        }

        if (($item['targetType'] ?? '') === 'post') {
            $category = Category::where('slug', $item['targetCategorySlug'])->first();

            if (! $category) {
                echo "Không tìm thấy category: {$item['targetCategorySlug']}\n";
                continue;
            }

            $slug = Str::slug($item['slug'] ?? $item['title']);

            $post = Post::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['targetName'] ?? $item['title'],
                    'description' => $item['description'] ?? null,
                    'content' => $item['content_text'] ?? null,
                    'title_seo' => $item['meta_title'] ?? null,
                    'description_seo' => $item['meta_description'] ?? null,
                    'canonical_seo' => '/' . $slug,
                    'status' => 1,
                ]
            );

            $post->categories()->syncWithoutDetaching([$category->id]);

            echo "Upserted post: {$post->name}\n";
        }
    }
});

echo "DONE\n";
