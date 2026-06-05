<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        try {
            $location = $request->input('location', 'header');

            $menus = Menu::query()
                ->select([
                    'id',
                    'name',
                    'topic',
                    'path',
                    'part_id',
                    'parent_id',
                    'status',
                    'order_position',
                ])
                ->where('location', $location)
                ->where('status', 1)
                ->orderBy('order_position', 'asc')
                ->get();

            if ($menus->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'OK',
                    'data'    => [],
                ], 200);
            }

            /*
             * Lấy toàn bộ category/post active để resolve chắc chắn cả dữ liệu cũ.
             *
             * Lý do:
             * - Có menu đúng topic + part_id.
             * - Có menu cũ chỉ lưu path như /bong-gan-day-chang.
             * - Có menu path sai prefix nhưng slug đúng.
             *
             * Vì vậy phải có map theo id và map theo slug.
             */
            $categories = Category::query()
                ->where('status', 1)
                ->get(['id', 'name', 'slug', 'type'])
                ->filter(fn($category) => !empty($category->slug));

            $posts = Post::query()
                ->where('status', 1)
                ->get(['id', 'name', 'slug'])
                ->filter(fn($post) => !empty($post->slug));

            $categoriesById = $categories->keyBy('id');
            $postsById = $posts->keyBy('id');

            $categoriesBySlug = $categories->keyBy(function ($category) {
                return trim((string) $category->slug, '/');
            });

            $postsBySlug = $posts->keyBy(function ($post) {
                return trim((string) $post->slug, '/');
            });

            $menuTree = $this->buildTreeFast(
                $menus,
                $categoriesById,
                $postsById,
                $categoriesBySlug,
                $postsBySlug
            );

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'data'    => $menuTree,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Menu API error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.server_error'),
                'data'    => [],
            ], 500);
        }
    }

    private function buildTreeFast(
        Collection $flat,
        Collection $categoriesById,
        Collection $postsById,
        Collection $categoriesBySlug,
        Collection $postsBySlug
    ): array {
        $byParent = $flat->groupBy(fn($menu) => $menu->parent_id ?? 0);

        $makeTree = function ($parentId) use (
            &$makeTree,
            $byParent,
            $categoriesById,
            $postsById,
            $categoriesBySlug,
            $postsBySlug
        ) {
            return ($byParent[$parentId] ?? collect())
                ->filter(fn($menu) => (int) $menu->status === 1)
                ->sortBy('order_position')
                ->map(function ($menu) use (
                    $makeTree,
                    $categoriesById,
                    $postsById,
                    $categoriesBySlug,
                    $postsBySlug
                ) {
                    $children = $makeTree($menu->id);

                    $node = [
                        'id'             => $menu->id,
                        'name'           => $menu->name,
                        'path'           => $this->resolveMenuPath(
                            $menu,
                            $categoriesById,
                            $postsById,
                            $categoriesBySlug,
                            $postsBySlug
                        ),
                        'status'         => (int) $menu->status,
                        'order_position' => (int) $menu->order_position,
                    ];

                    if (!empty($children)) {
                        $node['children'] = $children;
                    }

                    return $node;
                })
                ->values()
                ->all();
        };

        return $makeTree(0);
    }
    private function resolveMenuPath(
        Menu $menu,
        Collection $categoriesById,
        Collection $postsById,
        Collection $categoriesBySlug,
        Collection $postsBySlug
    ): ?string {
        $topic  = strtolower(trim((string) $menu->topic));
        $partId = (int) $menu->part_id;

        if ($topic === 'custom') {
            $path = trim((string) $menu->path);

            if ($path === '') {
                return null;
            }

            if (preg_match('/^https?:\/\//i', $path)) {
                return $path;
            }

            return '/' . ltrim($path, '/');
        }

        if ($topic === 'category' && $partId > 0) {
            $category = $categoriesById->get($partId);

            if ($category && !empty($category->slug)) {
                return $this->buildCategoryPath($category);
            }
        }

        if ($topic === 'post' && $partId > 0) {
            $post = $postsById->get($partId);

            if ($post && !empty($post->slug)) {
                return '/' . ltrim((string) $post->slug, '/');
            }
        }

        $slug = $this->extractSlugFromPath($menu->path);

        if ($slug) {
            $category = $categoriesBySlug->get($slug);

            if ($category && !empty($category->slug)) {
                return $this->buildCategoryPath($category);
            }

            $post = $postsBySlug->get($slug);

            if ($post && !empty($post->slug)) {
                return '/' . ltrim((string) $post->slug, '/');
            }
        }

        if (!empty($menu->path)) {
            return '/' . ltrim((string) $menu->path, '/');
        }

        return null;
    }

    private function buildCategoryPath(Category $category): string
    {
        $type = strtolower(trim((string) $category->type));
        $slug = ltrim((string) $category->slug, '/');

        return match ($type) {
            'service' => '/dich-vu/' . $slug,
            'post'    => '/bai-viet/' . $slug,
            default   => '/danh-muc/' . $slug,
        };
    }

    private function extractSlugFromPath(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $path = trim($path);

        /*
         * Nếu DB lỡ lưu full URL thì lấy pathname.
         */
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $parsedPath = parse_url($path, PHP_URL_PATH);
            $path = $parsedPath ?: $path;
        }

        $path = trim($path, '/');

        if ($path === '') {
            return null;
        }

        $segments = array_values(array_filter(explode('/', $path)));

        if (empty($segments)) {
            return null;
        }

        /*
         * /dich-vu/bong-gan-day-chang => bong-gan-day-chang
         * /bong-gan-day-chang         => bong-gan-day-chang
         */
        return end($segments) ?: null;
    }
}
