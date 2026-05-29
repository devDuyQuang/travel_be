<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RepairMenuPathsSeeder extends Seeder
{
    public function run(): void
    {
        $menus = Menu::query()->get();

        foreach ($menus as $menu) {
            $name = trim((string) $menu->name);
            $oldPath = trim((string) $menu->path);
            $slugFromPath = $oldPath ? trim($oldPath, '/') : Str::slug($name);

            // Nếu path cũ có prefix thì lấy slug cuối
            // VD: /dich-vu/gay-xuong => gay-xuong
            // VD: /bai-viet/abc => abc
            if (str_contains($slugFromPath, '/')) {
                $parts = explode('/', $slugFromPath);
                $slugFromPath = end($parts);
            }

            $category = Category::query()
                ->where(function ($q) use ($name, $slugFromPath) {
                    $q->where('slug', $slugFromPath)
                        ->orWhere('name', $name);
                })
                ->where('status', 1)
                ->first();

            if ($category) {
                $type = strtolower((string) $category->type);
                $slug = ltrim((string) $category->slug, '/');

                $path = match ($type) {
                    'service' => '/dich-vu/' . $slug,
                    'post'    => '/' . $slug,
                    default   => '/danh-muc/' . $slug,
                };

                $menu->forceFill([
                    'topic'   => 'category',
                    'part_id' => $category->id,
                    'path'    => $path,
                ])->save();

                continue;
            }

            $post = Post::query()
                ->where(function ($q) use ($name, $slugFromPath) {
                    $q->where('slug', $slugFromPath)
                        ->orWhere('name', $name);
                })
                ->where('status', 1)
                ->first();

            if ($post) {
                $menu->forceFill([
                    'topic'   => 'post',
                    'part_id' => $post->id,
                    'path'    => '/' . ltrim((string) $post->slug, '/'),
                ])->save();

                continue;
            }

            // Nếu không match category/post thì giữ nguyên path cũ
            // Không xóa để tránh làm mất link custom.
        }
    }
}
