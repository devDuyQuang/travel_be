<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Danh sách bài viết.
     *
     * Hỗ trợ:
     * - /post
     * - /post?category_slug=gay-xuong
     * - /post?category_id=14
     */

    public function resolve(Request $request, $domain, string $slug)
    {
        try {
            $post = Post::query()
                ->with(['categories:id,name,slug,type,parent_id'])
                ->where('slug', $slug)
                ->where('status', 1)
                ->select([
                    'id',
                    'name',
                    'slug',
                    'description',
                    'content',
                    'image',
                    'title_seo',
                    'description_seo',
                    'canonical_seo',
                    'created_at',
                    'updated_at',
                    'views',
                    'favorites',
                    'created_by',
                    'updated_by',
                ])
                ->first();

            if (! $post) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.no_data_found'),
                    'type' => null,
                    'data' => null,
                ], 404);
            }

            $tocData = $this->buildTocFromHtml($post->content ?? '');

            $post->content = $tocData['content'];
            $post->setAttribute('toc', $tocData['toc']);

            $mainCategory = $post->categories->first();
            $post->setAttribute('breadcrumbs', $this->buildPostBreadcrumbs($mainCategory, $post));

            $module = strtolower((string) ($mainCategory?->type ?? 'post'));

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'type' => 'post',
                'module' => $module,
                'category' => $mainCategory,
                'data' => $post,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Post API resolve error', [
                'slug' => $slug,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.server_error'),
                'type' => null,
                'data' => null,
            ], 500);
        }
    }
    public function index(Request $request)
    {
        try {
            $limit    = max(1, (int) $request->input('limit', 12));
            $sortName = $request->input('sort_name', 'id');
            $sortBy   = strtolower($request->input('sort_by', 'desc')) === 'asc' ? 'asc' : 'desc';

            $sortable = ['id', 'name', 'created_at', 'updated_at', 'views', 'favorites'];
            $sortName = in_array($sortName, $sortable, true) ? $sortName : 'id';

            $categorySlug = $request->input('category_slug');
            $categoryId   = $request->input('category_id');
            $type         = $request->input('type');

            $query = Post::query()
                ->where('status', 1)
                ->with(['categories:id,name,slug,type,parent_id']);

            if (!empty($type)) {
                $query->whereHas('categories', function ($q) use ($type) {
                    $q->whereRaw('LOWER(categories.type) = ?', [strtolower($type)]);
                });
            }
            if ($name = $request->input('name')) {
                $query->where('name', 'LIKE', "%{$name}%");
            }

            if (!empty($categorySlug)) {
                $category = Category::query()
                    ->where('slug', $categorySlug)
                    ->where('status', 1)
                    ->first();

                if ($category) {
                    $categoryIds = $this->descendantIdsIncludingSelf((int) $category->id);

                    $query->whereHas('categories', function ($q) use ($categoryIds) {
                        $q->whereIn('categories.id', $categoryIds);
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }
            }

            if (!empty($categoryId)) {
                $categoryIds = $this->descendantIdsIncludingSelf((int) $categoryId);

                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }

            if ($request->has('status') && $request->input('status') !== '') {
                $query->where('status', (int) $request->input('status'));
            }

            $posts = $query
                ->orderBy($sortName, $sortBy)
                ->select([
                    'id',
                    'name',
                    'slug',
                    'image',
                    'description',
                    'created_at',
                    'views',
                    'favorites',
                ])
                ->paginate($limit);

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'data'    => $posts,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Post API index error', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.server_error'),
                'data'    => [],
            ], 500);
        }
    }

    public function show(Request $request, $domain, string $id)
    {
        try {
            $post = Post::query()
                ->with(['categories:id,name,slug,type'])
                ->when(
                    is_numeric($id),
                    fn($q) => $q->where('id', (int) $id),
                    fn($q) => $q->where('slug', $id)
                )
                ->where('status', 1)
                ->select([
                    'id',
                    'name',
                    'slug',
                    'description',
                    'content',
                    'image',
                    'title_seo',
                    'description_seo',
                    'canonical_seo',
                    'created_at',
                    'updated_at',
                    'views',
                    'favorites',
                    'created_by',
                    'updated_by',
                ])
                ->first();

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.no_data_found'),
                    'data'    => null,
                ], 200);
            }

            $tocData = $this->buildTocFromHtml($post->content ?? '');

            $post->content = $tocData['content'];
            $post->setAttribute('toc', $tocData['toc']);

            $mainCategory = $post->categories->first();
            $post->setAttribute('breadcrumbs', $this->buildPostBreadcrumbs($mainCategory, $post));

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'data'    => $post,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Post API show error', [
                'id'    => $id,
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.server_error'),
                'data'    => null,
            ], 500);
        }
    }

    /**
     * Xóa bài viết.
     */
    public function deletePost(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID bài viết không được để trống',
            ], 400);
        }

        try {
            $post = Post::findOrFail($id);

            if (!empty($post->image)) {
                $domainSlug = preg_replace(
                    '/[^a-z0-9_\-]/i',
                    '_',
                    strtolower($request->route('domain') ?? 'default')
                );

                $expectedPrefix = "uploads/{$domainSlug}/";

                if (
                    str_starts_with($post->image, $expectedPrefix)
                    && Storage::disk('public')->exists($post->image)
                ) {
                    Storage::disk('public')->delete($post->image);
                }
            }

            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa bài viết thành công.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Delete post error', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa.',
            ], 500);
        }
    }

    /**
     * Datatable cho admin panel.
     *
     * Giữ nhẹ để tránh lỗi memory.
     */
    public function datatable(Request $request)
    {
        try {
            $posts = Post::query()
                ->with(['categories:id,name,slug,type'])
                ->select([
                    'id',
                    'name',
                    'slug',
                    'image',
                    'status',
                    'views',
                    'favorites',
                    'created_at',
                ])
                ->orderByDesc('id')
                ->get();

            $rows = $posts->map(function ($p) {
                $firstCategory = $p->categories->first();

                return [
                    'id'          => $p->id,
                    'name'        => $p->name ?? '',
                    'slug'        => $p->slug ?? '',
                    'status'      => (int) $p->status,
                    'views'       => (int) $p->views,
                    'favorites'   => (int) $p->favorites,
                    'image'       => $p->image ?? '',
                    'category'    => $firstCategory?->name ?? '',
                    'created_at'  => optional($p->created_at)->format('d/m/Y H:i'),
                    '__details'   => '',
                ];
            })->values();

            return response()->json(['data' => $rows]);
        } catch (\Throwable $e) {
            Log::error('Post API datatable error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bài viết liên quan.
     *
     * Hỗ trợ:
     * - /post/{id}/related
     * - /post/{slug}/related
     * - ?limit=6  (mặc định 6)
     * - ?exclude_id=123  (tuỳ chọn, loại trừ thêm 1 bài)
     */
    public function relatedPosts(Request $request, $domain, string $id)
    {
        try {
            // 1. Tìm bài viết gốc để lấy danh mục
            $post = Post::query()
                ->when(
                    is_numeric($id),
                    fn($q) => $q->where('id', (int) $id),
                    fn($q) => $q->where('slug', $id)
                )
                ->where('status', 1)
                ->with(['categories:id'])
                ->select(['id'])
                ->first();

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.no_data_found'),
                    'data'    => [],
                ], 200);
            }

            $categoryIds = $post->categories->pluck('id')->all();
            $limit       = max(1, min(20, (int) $request->input('limit', 6)));

            // ID cần loại trừ (bài hiện tại + tuỳ chọn exclude_id thêm)
            $excludeIds = [$post->id];
            if ($extraExclude = $request->input('exclude_id')) {
                $excludeIds[] = (int) $extraExclude;
            }

            // 2. Lấy bài cùng danh mục
            $related = Post::query()
                ->where('status', 1)
                ->whereNotIn('id', $excludeIds)
                ->when(!empty($categoryIds), function ($q) use ($categoryIds) {
                    $q->whereHas('categories', function ($q2) use ($categoryIds) {
                        $q2->whereIn('categories.id', $categoryIds);
                    });
                })
                ->with(['categories:id,name,slug,type'])
                ->select([
                    'id',
                    'name',
                    'slug',
                    'image',
                    'description',
                    'created_at',
                    'views',
                    'favorites',
                ])
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'data'    => $related,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Post API relatedPosts error', [
                'id'    => $id,
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.server_error'),
                'data'    => [],
            ], 500);
        }
    }

    /**
     * Sitemap.
     */
    public function sitemap()
    {
        $posts = Post::query()
            ->where('status', 1)
            ->select('slug', 'updated_at')
            ->get();

        $data = $posts->map(fn($post) => [
            'slug'       => $post->slug,
            'updated_at' => $post->updated_at?->toAtomString(),
        ])->all();

        return response()->json($data);
    }

    private function descendantIdsIncludingSelf(int $categoryId): array
    {
        $ids = [$categoryId];
        $queue = [$categoryId];

        while (!empty($queue)) {
            $children = Category::query()
                ->whereIn('parent_id', $queue)
                ->where('status', 1)
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->all();

            $children = array_values(array_diff($children, $ids));

            if (empty($children)) {
                break;
            }

            $ids = array_merge($ids, $children);
            $queue = $children;
        }

        return array_values(array_unique($ids));
    }

    private function buildPostBreadcrumbs(?Category $category, Post $post): array
    {
        $breadcrumbs = [
            [
                'name' => 'Trang chủ',
                'slug' => '/',
                'url' => '/',
                'active' => false,
            ],
        ];

        if ($category) {
            $type = strtolower((string) $category->type);

            $prefix = $type === 'service'
                ? 'dich-vu'
                : ($type === 'post' ? 'bai-viet' : '');

            $prefixName = $type === 'service'
                ? 'Dịch vụ'
                : ($type === 'post' ? 'Bài viết' : null);

            $trail = [];
            $current = $category;

            while ($current) {
                $trail[] = [
                    'name' => $current->name,
                    'slug' => $prefix ? $prefix . '/' . $current->slug : $current->slug,
                    'url' => '/' . ($prefix ? $prefix . '/' . $current->slug : $current->slug),
                    'type' => $current->type,
                    'active' => false,
                ];

                if (empty($current->parent_id)) {
                    break;
                }

                $current = Category::query()
                    ->select(['id', 'name', 'slug', 'type', 'parent_id'])
                    ->where('status', 1)
                    ->find($current->parent_id);
            }

            $breadcrumbs = array_merge($breadcrumbs, array_reverse($trail));

            $breadcrumbs[] = [
                'name' => $post->name,
                'slug' => $post->slug,
                'url' => '/' . $post->slug,
                'active' => true,
            ];

            return $breadcrumbs;
        }

        $breadcrumbs[] = [
            'name' => $post->name,
            'slug' => $post->slug,
            'url' => '/' . $post->slug,
            'active' => true,
        ];

        return $breadcrumbs;
    }
    private function buildTocFromHtml(?string $html): array
    {
        $html = (string) $html;

        if (trim($html) === '') {
            return [
                'content' => $html,
                'toc' => [],
            ];
        }

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument('1.0', 'UTF-8');

        $dom->loadHTML(
            '<?xml encoding="UTF-8"><div id="toc-root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $headings = $xpath->query('//h2 | //h3');

        $toc = [];
        $usedIds = [];

        foreach ($headings as $heading) {
            if (!$heading instanceof \DOMElement) {
                continue;
            }

            $text = trim($heading->textContent);

            if ($text === '') {
                continue;
            }

            $level = strtolower($heading->nodeName) === 'h3' ? 3 : 2;

            $currentId = $heading->getAttribute('id');

            $id = $currentId !== ''
                ? $currentId
                : Str::slug($text);

            if ($id === '') {
                $id = 'section';
            }

            $baseId = $id;
            $counter = 2;

            while (in_array($id, $usedIds, true)) {
                $id = $baseId . '-' . $counter;
                $counter++;
            }

            $usedIds[] = $id;
            $heading->setAttribute('id', $id);

            $toc[] = [
                'id' => $id,
                'text' => $text,
                'level' => $level,
            ];
        }

        $root = $dom->getElementById('toc-root');
        $content = '';

        if ($root) {
            foreach ($root->childNodes as $child) {
                $content .= $dom->saveHTML($child);
            }
        }

        return [
            'content' => $content ?: $html,
            'toc' => $toc,
        ];
    }
}
