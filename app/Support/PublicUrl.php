<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use Illuminate\Support\Collection;

class PublicUrl
{
    public static function category(Category $category): ?string
    {
        if (empty($category->slug)) {
            return null;
        }

        $slug = ltrim((string) $category->slug, '/');
        $type = strtolower(trim((string) $category->type));

        return match ($type) {
            'service' => '/dich-vu/' . $slug,
            'post'    => '/tin-tuc?category=' . $slug,
            default   => '/danh-muc/' . $slug,
        };
    }

    public static function post(Post $post): ?string
    {
        if (empty($post->slug)) {
            return null;
        }

        return '/tin-tuc/' . ltrim((string) $post->slug, '/');
    }

    public static function menu(
        Menu $menu,
        ?Collection $categories = null,
        ?Collection $posts = null
    ): ?string {
        $topic = strtolower(trim((string) $menu->topic));
        $partId = (int) $menu->part_id;

        if ($topic === 'category' && $partId > 0) {
            $category = $categories?->get($partId) ?? Category::query()->find($partId);

            return $category ? self::category($category) : null;
        }

        if ($topic === 'post' && $partId > 0) {
            $post = $posts?->get($partId) ?? Post::query()->find($partId);

            return $post ? self::post($post) : null;
        }

        if (!empty($menu->path)) {
            return '/' . ltrim((string) $menu->path, '/');
        }

        return null;
    }
}
