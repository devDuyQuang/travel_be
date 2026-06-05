<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Post();
    }

    public function index()
    {
        $filterCategories = Category::query()
            ->leftJoin('category_post', 'category_post.category_id', '=', 'categories.id')
            ->select([
                'categories.id',
                'categories.name',
                DB::raw('COUNT(category_post.post_id) as posts_count'),
            ])
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('categories.name')
            ->get();

        $filterCreators = DB::table('users')
            ->leftJoin('posts', 'posts.created_by', '=', 'users.id')
            ->select([
                'users.id',
                'users.name',
                DB::raw('COUNT(posts.id) as posts_count'),
            ])
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();

        return view(module() . '.main', compact('filterCategories', 'filterCreators'));
    }

    public function datatable(Request $request)
    {
        $draw = (int) $request->get('draw', 1);

        $length = (int) $request->get('length', 10);
        $start = (int) $request->get('start', 0);

        if ($length < 1) {
            $length = 10;
        }

        if ($length > 100) {
            $length = 100;
        }

        $search = trim((string) $request->input('search.value', ''));
        $categoryId = (int) $request->input('category_id', 0);
        $creatorId = (int) $request->input('created_by', 0);
        $baseQuery = DB::table('posts')
            ->leftJoin('users as creators', 'creators.id', '=', 'posts.created_by')
            ->leftJoin('users as updaters', 'updaters.id', '=', 'posts.updated_by')
            ->select([
                'posts.id',
                'posts.name',
                'posts.slug',
                'posts.image',
                'posts.status',
                'posts.views',
                'posts.favorites',
                'posts.created_at',
                'posts.updated_at',
                'creators.name as creator_name',
                'updaters.name as updater_name',
            ]);

        if ($categoryId > 0) {
            $baseQuery->whereExists(function ($q) use ($categoryId) {
                $q->select(DB::raw(1))
                    ->from('category_post')
                    ->whereColumn('category_post.post_id', 'posts.id')
                    ->where('category_post.category_id', $categoryId);
            });
        }

        if ($creatorId > 0) {
            $baseQuery->where('posts.created_by', $creatorId);
        }
        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('posts.name', 'like', '%' . $search . '%')
                    ->orWhere('posts.slug', 'like', '%' . $search . '%');
            });
        }

        $recordsTotal = DB::table('posts')->count();

        $recordsFiltered = (clone $baseQuery)->count();

        $items = $baseQuery
            ->orderByDesc('posts.id')
            ->offset($start)
            ->limit($length)
            ->get();

        $postIds = $items->pluck('id')->values()->all();

        $categoriesByPost = collect();

        if (!empty($postIds)) {
            $categoriesByPost = DB::table('category_post')
                ->join('categories', 'categories.id', '=', 'category_post.category_id')
                ->whereIn('category_post.post_id', $postIds)
                ->select([
                    'category_post.post_id',
                    'categories.name',
                ])
                ->get()
                ->groupBy('post_id')
                ->map(function ($rows) {
                    return $rows->pluck('name')->filter()->values()->all();
                });
        }

        $rows = $items->map(function ($p) use ($categoriesByPost) {
            $slug = trim((string) ($p->slug ?? ''), '/');

            return [
                'id'           => $p->id,
                'name'         => $p->name ?? '',
                'slug'         => $p->slug ?? '',
                'image'        => $p->image ?? '',
                'status'       => (int) ($p->status ?? 0),
                'views'        => (int) ($p->views ?? 0),
                'favorites'    => (int) ($p->favorites ?? 0),
                'created_at'   => $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') : '',
                'updated_at'   => $p->updated_at ? \Carbon\Carbon::parse($p->updated_at)->format('d/m/Y H:i') : '',

                'creator'      => $p->creator_name ?: '—',
                'creator_name' => $p->creator_name ?: '—',

                'updater'      => $p->updater_name ?: '—',
                'updater_name' => $p->updater_name ?: '—',
                'categories'   => $categoriesByPost->get($p->id, []),

                // Theo yêu cầu thầy: bài viết thật mở thẳng /slug
                'public_url'   => $slug !== '' ? '/' . $slug : '',

                '__details'    => '',
            ];
        })->values();

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $rows,
        ]);
    }

    public function create()
    {
        $categories = $this->getPostCategoryOptions();

        return view(module() . '.create', compact('categories'));
    }

    public function edit(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);
        $item->load('categories:id,name');

        $currentImageUrl = function_exists('normalize_image_url')
            ? normalize_image_url($item->image, module())
            : $item->image;

        $categories = $this->getPostCategoryOptions();

        $selectedCategoryIds = old(
            'category_ids',
            $item->categories->pluck('id')->toArray()
        );

        return view(module() . '.edit', [
            'item'                => $item,
            'currentImageUrl'     => $currentImageUrl,
            'categories'          => $categories,
            'selectedCategoryIds' => $selectedCategoryIds,
        ]);
    }

    public function store(Request $request)
    {
        $table = $this->model->getTable();

        $validated = $request->validate([
            'name'             => "required|string|max:255|unique:{$table},name",
            'slug'             => "nullable|string|max:255|unique:{$table},slug",
            'description'      => ['nullable', 'string'],
            'content'          => ['nullable', 'string'],
            'title_seo'        => ['nullable', 'string', 'max:255'],
            'description_seo'  => ['nullable', 'string'],
            'canonical_seo'    => ['nullable', 'string', 'max:255'],
            'file'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'status'           => ['nullable', 'boolean'],
            'category_ids'     => ['nullable', 'array'],
            'category_ids.*'   => ['integer', 'exists:categories,id'],
        ]);

        DB::beginTransaction();
        $newPath = null;

        try {
            $post = $this->model;
            $post->fill($validated);

            $baseSlug = $validated['slug'] ?? $validated['name'];
            $post->slug = $this->generateUniqueSlug($baseSlug);

            $post->status = (int) ($validated['status'] ?? 1);

            if (Auth::check()) {
                $post->created_by = Auth::id();
                $post->updated_by = Auth::id();
            }

            $domainSlug = $this->sanitizeDomain($request->route('domain') ?? 'default');
            $moduleName = module() ?? 'default';
            $uploadDir  = "uploads/{$domainSlug}/{$moduleName}";

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = $post->slug . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $newPath = $file->storeAs($uploadDir, $filename, 'public');
                $post->image = $newPath;
            }

            $post->save();

            $post->categories()->sync($request->input('category_ids', []));

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => __('messages.data_saved'),
                'redirect_url' => panel_route(module() . '.index'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($newPath && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }

            Log::error("Error inserting post: {$e->getMessage()}", ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => __('messages.unexpected_error'),
            ], 500);
        }
    }

    public function update(Request $request, $domain, $id)
    {
        $post = $this->model->findOrFail($id);
        $table = $this->model->getTable();

        $data = $request->validate([
            'name'             => ['required', 'string', 'max:255', Rule::unique($table, 'name')->ignore($id)],
            'slug'             => ['nullable', 'string', 'max:255', Rule::unique($table, 'slug')->ignore($id)],
            'description'      => ['nullable', 'string'],
            'content'          => ['nullable', 'string'],
            'title_seo'        => ['nullable', 'string', 'max:255'],
            'description_seo'  => ['nullable', 'string'],
            'canonical_seo'    => ['nullable', 'string', 'max:255'],
            'file'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'remove_file'      => ['nullable', 'boolean'],
            'status'           => ['nullable', 'boolean'],
            'category_ids'     => ['nullable', 'array'],
            'category_ids.*'   => ['integer', 'exists:categories,id'],
        ]);

        $data['status'] = $request->boolean('status', true) ? 1 : 0;

        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        $oldPath    = $post->image;
        $wantRemove = $request->boolean('remove_file');
        $newPath    = null;

        $domainSlug = $this->sanitizeDomain($domain ?? 'default');
        $moduleName = module() ?? 'default';
        $uploadDir  = "uploads/{$domainSlug}/{$moduleName}";

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $baseSlug = $data['slug'] ?? $data['name'];
            $filename = Str::slug($baseSlug) . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $newPath = $file->storeAs($uploadDir, $filename, 'public');
            $data['image'] = $newPath;
        }

        if ($wantRemove && !$newPath) {
            $data['image'] = null;
        }

        DB::beginTransaction();

        try {
            if (empty($data['slug']) && !empty($data['name'])) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $id);
            }

            $post->fill($data)->save();

            $post->categories()->sync($request->input('category_ids', []));

            if ($oldPath && ($newPath || $wantRemove)) {
                $expectedPrefix = "uploads/{$domainSlug}/";

                if (str_starts_with($oldPath, $expectedPrefix) && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            DB::commit();

            return response()->json([
                'message'  => __('messages.data_saved') ?: 'Đã lưu thành công.',
                'redirect' => panel_route(module() . '.index'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($newPath && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }

            Log::error("Update post failed: " . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message' => __('messages.unexpected_error') ?: 'Có lỗi xảy ra.',
            ], 500);
        }
    }

    private function getPostCategoryOptions(): array
    {
        $items = Category::query()
            ->where('status', 1)
            ->where(function ($q) {
                $q->whereIn(DB::raw('LOWER(type)'), ['post', 'service'])
                    ->orWhereNull('type')
                    ->orWhere('type', '');
            })
            ->select('id', 'name', 'parent_id')
            ->orderByRaw('parent_id IS NOT NULL')
            ->orderBy('parent_id')
            ->orderBy('id')
            ->get();

        return collect(recursive($items))
            ->pluck('label', 'value')
            ->toArray();
    }

    public function toggleStatus(Request $request, $domain, $id)
    {
        $post = $this->model->findOrFail($id);

        $post->status = (int) $post->status === 1 ? 0 : 1;

        if (Auth::check()) {
            $post->updated_by = Auth::id();
        }

        $post->save();

        return response()->json([
            'success' => true,
            'status' => (int) $post->status,
        ]);
    }

  public function destroy(Request $request, $domain, $id)
{
    DB::beginTransaction();

    try {
        $post = $this->model->findOrFail($id);

        $imagePath = $post->image;

        // Xoá liên kết danh mục trước, tránh lỗi khóa ngoại category_post
        if (method_exists($post, 'categories')) {
            $post->categories()->detach();
        }

        // Xoá ảnh nếu có
        if (!empty($imagePath)) {
            $domainSlug = $this->sanitizeDomain($domain ?? 'default');
            $expectedPrefix = "uploads/{$domainSlug}/";

            if (
                str_starts_with($imagePath, $expectedPrefix)
                && Storage::disk('public')->exists($imagePath)
            ) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        // Không có SoftDeletes thì delete() là xoá thật khỏi DB
        $post->delete();

        DB::commit();

        $msg = 'Xóa dịch vụ thành công.';

        return $request->ajax() || $request->wantsJson()
            ? response()->json([
                'success' => true,
                'message' => $msg,
            ])
            : redirect()->to(panel_route(module() . '.index'))->with('success', $msg);

    } catch (\Throwable $e) {
        DB::rollBack();

        Log::error('Delete post error: ' . $e->getMessage(), [
            'post_id' => $id,
            'domain' => $domain,
            'trace' => $e->getTraceAsString(),
        ]);

        $msg = 'Có lỗi xảy ra khi xóa: ' . $e->getMessage();

        return $request->ajax() || $request->wantsJson()
            ? response()->json([
                'success' => false,
                'message' => $msg,
            ], 500)
            : redirect()->back()->with('error', $msg);
    }
}

    private function generateUniqueSlug($baseSlug, $excludeId = null)
    {
        $slug = Str::slug($baseSlug);
        $original = $slug;
        $count = 1;

        while (Post::where('slug', $slug)->where('id', '!=', $excludeId ?? 0)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    private function sanitizeDomain($domain)
    {
        return preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($domain));
    }
}
