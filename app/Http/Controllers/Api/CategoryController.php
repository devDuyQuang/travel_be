<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Danh sách danh mục (cây) - active
     */
    public function index(Request $request)
    {
        try {
            $sortName = $request->input('sort_name', 'sort');
            $sortBy   = strtolower($request->input('sort_by', 'asc')) === 'asc' ? 'asc' : 'desc';

            $sortable = ['id', 'name', 'created_at', 'sort'];
            $sortName = in_array($sortName, $sortable, true) ? $sortName : 'sort';

            $type = $request->input('type');
            $home = $request->input('home');

            $categories = Category::query()
                ->where('status', 1)
                ->when($type, function ($q) use ($type) {
                    $q->where('type', $type);
                })

                ->when($home !== null, function ($q) use ($home) {
                    $q->where('home', (int) $home);
                })

                ->select(['id', 'name', 'slug', 'parent_id', 'sort', 'type', 'layout_key', 'image', 'icon'])

                ->orderBy($sortName, $sortBy)
                ->get();

            if ($categories->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'OK',
                    'data'    => [],
                    'meta'    => ['empty' => true],
                ], 200);
            }

            if ($home !== null) {
                $tree = $categories->map(function ($c) {
                    return [
                        'name'  => $c->name,
                        'slug'  => $c->slug,
                        'type'  => $c->type,
                        'layout_key' => $c->layout_key,
                        'image' => $c->image,
                        'icon'  => $c->icon,
                    ];
                })->values()->all();
            } else {
                $tree = $this->buildTreeFast($categories);
                $this->removeParentIdField($tree);
            }

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'data'    => $tree,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API Category index error', [
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
     * Chi tiết danh mục + danh sách bài viết (gồm con)
     */
    public function show(Request $request, $domain, string $id)
    {
        try {
            $allowed = [
                'id',
                'name',
                'slug',
                'type',
                'layout_key',
                'image',
                'description',
                'content',
                'parent_id',
                'title_seo',
                'description_seo',
                'canonical_seo',
                'created_at',
                'updated_at',
                'icon',
            ];

            $fieldsInput = $request->input('fields');
            $fields = $this->parseFields($fieldsInput, $allowed);

            // Dù FE truyền fields hay không, vẫn cần id + parent_id để xử lý logic.
            if (!empty($fields)) {
                foreach (['id', 'parent_id', 'slug', 'name', 'type', 'layout_key'] as $requiredField) {
                    if (!in_array($requiredField, $fields, true)) {
                        $fields[] = $requiredField;
                    }
                }
            }

            $categoryQuery = Category::query()
                ->when(
                    is_numeric($id),
                    fn($q) => $q->where('id', (int) $id),
                    fn($q) => $q->where('slug', $id)
                )
                ->where('status', 1);

            if (!empty($fields)) {
                $categoryQuery->select($fields);
            }

            $category = $categoryQuery->first();

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.no_data_found'),
                    'data'    => null,
                ], 200);
            }

            $categoryIds = $this->descendantIdsIncludingSelf((int) $category->id);

            $limit = max(1, (int) $request->input('limit', 12));
            $page  = max(1, (int) $request->input('page', 1));

            $sortName = $request->input('sort_name', 'created_at');
            $sortBy   = strtolower($request->input('sort_by', 'desc')) === 'asc' ? 'asc' : 'desc';

            $sortable = ['id', 'name', 'created_at', 'updated_at', 'views', 'favorites'];
            $sortName = in_array($sortName, $sortable, true) ? $sortName : 'created_at';

            $posts = Post::query()
                ->where('status', 1)
                ->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                })
                ->select([
                    'id',
                    'name',
                    'slug',
                    'image',
                    'description',
                    'created_at',
                    'updated_at',
                    'views',
                    'favorites',
                ])
                ->orderBy($sortName, $sortBy)
                ->paginate($limit, ['*'], 'page', $page);

            $posts->getCollection()->each(function ($post) {
                $post->image_url = function_exists('normalize_image_url')
                    ? normalize_image_url($post->image, 'post')
                    : $post->image;
            });

            /*
        |--------------------------------------------------------------------------
        | Breadcrumbs
        |--------------------------------------------------------------------------
        */
            $breadcrumbs = [
                [
                    'name' => 'Trang chủ',
                    'slug' => '/',
                    'url' => '/',
                    'active' => false,
                ],
            ];

            $type = strtolower((string) ($category->type ?? ''));

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
                    'name'   => $current->name,
                    'slug'   => $prefix ? $prefix . '/' . $current->slug : $current->slug,
                    'url'    => '/' . ($prefix ? $prefix . '/' . $current->slug : $current->slug),
                    'type'   => $current->type ?? null,
                    'active' => false,
                ];

                if (empty($current->parent_id)) {
                    break;
                }

                $current = Category::query()
                    ->select(['id', 'name', 'slug', 'type', 'layout_key', 'parent_id'])
                    ->where('status', 1)
                    ->find($current->parent_id);
            }

            $trail = array_reverse($trail);

            foreach ($trail as $index => &$item) {
                $item['active'] = $index === count($trail) - 1;
            }

            unset($item);

            $breadcrumbs = array_merge($breadcrumbs, $trail);

            /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */
            $data = $category->toArray();

            unset($data['parent_id']);

            $tocData = $this->buildTocFromHtml($category->content ?? '');

            $data['content'] = $tocData['content'];
            $data['toc'] = $tocData['toc'];

            $data['posts'] = $posts;
            $data['breadcrumbs'] = $breadcrumbs;

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'data'    => $data,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API Category show error', [
                'id'    => $id,
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.server_error'),
                'data'    => null,
            ], 500);
        }
    }

    // === Helper Methods ===

    /**
     * Parse fields từ query string
     */
    private function parseFields($input, array $allowed): array
    {
        if ($input === null) return [];

        $fields = array_filter(array_map('trim', explode(',', $input)));
        $fields = array_values(array_unique($fields));
        $fields = array_values(array_intersect($fields, $allowed));

        if (!in_array('id', $fields, true)) {
            array_unshift($fields, 'id');
        }

        return $fields;
    }

    /**
     * Xây dựng cây danh mục nhanh
     */
    private function buildTreeFast(Collection $flat): array
    {
        $byParent = $flat->groupBy(fn($c) => $c->parent_id ?? 0);

        $make = function ($parentId) use (&$make, $byParent) {
            return ($byParent[$parentId] ?? collect())->map(function ($c) use ($make) {
                $node = [
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'type' => $c->type,
                    'layout_key' => $c->layout_key,
                    'image' => $c->image,
                    'icon' => $c->icon,
                ];
                $children = $make($c->id);
                if ($children) {
                    $node['children'] = $children;
                }
                return $node;
            })->values()->all();
        };

        return $make(0);
    }

    /**
     * Xóa trường parent_id khỏi cây
     */
    private function removeParentIdField(array &$nodes): void
    {
        foreach ($nodes as &$node) {
            if (isset($node['children'])) {
                $this->removeParentIdField($node['children']);
            }
        }
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
