<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Support\PublicUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Category();
    }

    public function index()
    {
        return view(module() . '.main');
    }

    public function datatable(Request $request)
    {
        $draw = (int) $request->get('draw', 1);
        $search = $request->input('search.value', '');

        $query = Category::query()
            ->select(
                'id',
                'name',
                'slug',
                'type',
                'parent_id',
                'status',
                'home',
                'sort',
                'order_position',
                'created_at',
                'created_by'
            )
            ->with('creator:id,name');

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $total = Category::count();

        $allItems = $query
            ->orderBy('order_position')
            ->orderBy('id')
            ->get();

        $filtered = $allItems->count();
        $byId = $allItems->keyBy('id');

        $byParent = $allItems->groupBy(fn($item) => $item->parent_id);
        $sorted = collect();

        $buildTree = function ($parentId, $depth) use (&$buildTree, $byParent, &$sorted) {
            $children = $byParent
                ->get($parentId, collect())
                ->sortBy('order_position')
                ->values();

            foreach ($children as $child) {
                $sorted->push([
                    'item' => $child,
                    'depth' => $depth,
                ]);

                $buildTree($child->id, $depth + 1);
            }
        };

        $buildTree(null, 0);

        $data = $sorted->map(function ($entry) use ($byId) {
            $c = $entry['item'];

            $slugs = [];
            $cur = $c;

            while ($cur) {
                $slugs[] = $cur->slug ?? '';
                $cur = $cur->parent_id ? $byId->get($cur->parent_id) : null;
            }

            $fullSlug = implode('/', array_reverse(array_filter($slugs)));

            return [
                'id'           => $c->id,
                'drag_handle'  => '',
                'name'         => $c->name ?? '—',
                'slug'         => $c->slug ?? '',
                'type'         => $c->type ?? '—',
                'parent_id' => $c->parent_id,
                'depth'        => $entry['depth'],
                'status'       => (int) $c->status,
                'home'         => (int) $c->home,
                'created_at'   => $c->created_at ? $c->created_at->format('d/m/Y H:i') : '',
                'creator_name' => $c->creator ? $c->creator->name : '—',
                'full_slug'    => $fullSlug,
                'public_url'   => PublicUrl::category($c),
                'actions'      => null,

            ];
        })->values();

        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 100);

        if ($length < 1) {
            $length = 1000;
        }

        $dataPage = $data->slice($start, $length)->values();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $dataPage,
        ]);
    }

    public function create()
    {
        $type = request('type');

        $items = $this->model::select('id', 'name', 'parent_id')
            ->where('status', 1)
            ->when($type, fn($q) => $q->where('type', $type))
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        $parents = recursive($items);

        return view(module() . '.create', [
            'items'   => $items,
            'parents' => $parents,
        ]);
    }

    public function edit(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);

        $type = request('type', $item->type ?? null);

        $excludeIds   = $this->descendantIds($item->id);
        $excludeIds[] = (int) $item->id;

        $candidates = $this->model::select('id', 'name', 'parent_id', 'status')
            ->when($type, fn($q) => $q->where('type', $type))
            ->whereNotIn('id', $excludeIds)
            ->where(function ($q) use ($item) {
                $q->where('status', 1);

                if (!empty($item->parent_id)) {
                    $q->orWhere('id', $item->parent_id);
                }
            })
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        $parents = recursive(
            items: $candidates,
            selected: $item->parent_id,
            exclude: $item->id
        );

        $currentImageUrl = normalize_image_url(
            $item->image ?? null,
            module()
        );

        $currentIconUrl = normalize_image_url(
            $item->icon ?? null,
            module()
        );

        return view(module() . '.edit', [
            'item'             => $item,
            'items'            => $candidates,
            'parents'          => $parents,
            'selectedParentId' => old('parent_id', $item->parent_id),
            'currentImageUrl'  => $currentImageUrl,
            'currentIconUrl'   => $currentIconUrl,
        ]);
    }

    protected function descendantIds(int $rootId): array
    {
        $desc = [];
        $queue = [$rootId];

        while (!empty($queue)) {
            $parentIds = $queue;
            $queue = [];

            $children = $this->model::select('id')
                ->whereIn('parent_id', $parentIds)
                ->pluck('id')
                ->all();

            foreach ($children as $cid) {
                if (!in_array($cid, $desc, true)) {
                    $desc[] = (int) $cid;
                    $queue[] = (int) $cid;
                }
            }
        }

        return $desc;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'            => "required|string|max:255|unique:{$this->model->table},name",
            'slug'            => "required|string|max:255|unique:{$this->model->table},slug",
            'parent_id'       => "nullable|integer|exists:{$this->model->table},id",
            'type' => ['required', 'string', Rule::in(['post', 'product'])],
            'description'     => 'nullable|string',
            'content'         => 'nullable|string',
            'file'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'icon_file'       => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'title_seo'       => 'nullable|string|max:255',
            'description_seo' => 'nullable|string',
            'canonical_seo'   => 'nullable|string|max:255',
            'status'          => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        $newPath = null;
        $newIconPath = null;

        try {
            $maxSort = $this->model::where('type', $request->input('type'))
                ->when($request->input('parent_id'), function ($query) use ($request) {
                    return $query->where('parent_id', $request->input('parent_id'));
                })
                ->max('sort');

            $newSort = $maxSort ? $maxSort + 1 : 1;

            $maxOrderPos = (int) $this->model::max('order_position');
            $newOrderPos = $maxOrderPos + 1;

            $categoryData = $validatedData;
            unset($categoryData['file'], $categoryData['icon_file']);

            $category = $this->model->fill($categoryData);
            $category->sort = $newSort;
            $category->order_position = $newOrderPos;
            $category->slug = Str::slug($validatedData['slug']);
            $category->type = strtolower($validatedData['type']);
            $category->status = 1;

            if (auth()->check()) {
                $category->created_by = auth()->id();
                $category->updated_by = auth()->id();
            }

            $domainParam = $request->route('domain') ?? null;
            $domainSlug = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($domainParam ?? 'default'));
            $moduleName = module() ?? 'default';
            $uploadDir = "uploads/{$domainSlug}/{$moduleName}";

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = $category->slug . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $newPath = $file->storeAs($uploadDir, $filename, 'public');
                $category->image = $newPath;
            }

            if ($request->hasFile('icon_file')) {
                $iconFile = $request->file('icon_file');
                $iconFilename = $category->slug . '_icon_' . time() . '_' . uniqid() . '.' . $iconFile->getClientOriginalExtension();
                $newIconPath = $iconFile->storeAs($uploadDir, $iconFilename, 'public');
                $category->icon = $newIconPath;
            }

            $category->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.data_saved'),
                'redirect_url' => panel_route(module() . '.index'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            if (!empty($newPath) && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }

            if (!empty($newIconPath) && Storage::disk('public')->exists($newIconPath)) {
                Storage::disk('public')->delete($newIconPath);
            }

            Log::error('Error inserting ' . module() . ': ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.unexpected_error'),
            ], 500);
        }
    }

    public function update(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);

        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'slug'            => ['required', 'string', 'max:255', Rule::unique($this->model->table, 'slug')->ignore($id)],
            'parent_id'       => ['nullable', 'integer', Rule::exists($this->model->table, 'id')],
            'type' => ['required', 'string', Rule::in(['post', 'product'])],
            'description'     => ['nullable', 'string'],
            'content'         => ['nullable', 'string'],
            'title_seo'       => ['nullable', 'string', 'max:255'],
            'description_seo' => ['nullable', 'string'],
            'canonical_seo'   => ['nullable', 'string', 'max:255'],
            'file'            => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'remove_file'     => ['nullable', 'boolean'],
            'icon_file'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:5120'],
            'remove_icon'     => ['nullable', 'boolean'],
        ]);
        $data['type'] = strtolower($data['type']);
        $data['slug'] = Str::slug($data['slug']);

        if (!empty($data['parent_id']) && (int) $data['parent_id'] === (int) $id) {
            return response()->json([
                'message' => 'Không thể chọn chính nó làm cha.',
            ], 422);
        }

        $oldPath = $item->image;
        $oldIconPath = $item->icon;

        $wantRemove = $request->boolean('remove_file');
        $wantRemoveIcon = $request->boolean('remove_icon');

        $domainSlug = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($domain ?? 'default'));
        $moduleName = module() ?? 'default';
        $uploadDir = "uploads/{$domainSlug}/{$moduleName}";

        $newPath = null;
        $newIconPath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $baseSlug = isset($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name'] ?? 'file');
            $filename = $baseSlug . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $newPath = $file->storeAs($uploadDir, $filename, 'public');
            $data['image'] = $newPath;
        }

        if ($wantRemove && !$newPath) {
            $data['image'] = null;
        }

        if ($request->hasFile('icon_file')) {
            $iconFile = $request->file('icon_file');
            $baseSlug = isset($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name'] ?? 'icon');
            $iconFilename = $baseSlug . '_icon_' . time() . '_' . uniqid() . '.' . $iconFile->getClientOriginalExtension();
            $newIconPath = $iconFile->storeAs($uploadDir, $iconFilename, 'public');
            $data['icon'] = $newIconPath;
        }

        if ($wantRemoveIcon && !$newIconPath) {
            $data['icon'] = null;
        }

        unset(
            $data['file'],
            $data['remove_file'],
            $data['icon_file'],
            $data['remove_icon']
        );

        DB::beginTransaction();

        try {
            if (auth()->check()) {
                $data['updated_by'] = auth()->id();
            }

            $data['type'] = strtolower($data['type']);

            $item->fill(array_merge($data, [
                'status' => $item->status,
            ]))->save();

            if ($oldPath && ($newPath || $wantRemove)) {
                $expectedPrefix = "uploads/{$domainSlug}/";

                if (strpos($oldPath, $expectedPrefix) === 0 && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                } else {
                    Log::info('Skip deleting old file: belongs to different domain folder', [
                        'oldPath' => $oldPath,
                        'expectedPrefix' => $expectedPrefix,
                    ]);
                }
            }

            if ($oldIconPath && ($newIconPath || $wantRemoveIcon)) {
                $expectedPrefix = "uploads/{$domainSlug}/";

                if (strpos($oldIconPath, $expectedPrefix) === 0 && Storage::disk('public')->exists($oldIconPath)) {
                    Storage::disk('public')->delete($oldIconPath);
                } else {
                    Log::info('Skip deleting old icon: belongs to different domain folder', [
                        'oldIconPath' => $oldIconPath,
                        'expectedPrefix' => $expectedPrefix,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message'  => __('messages.data_saved') ?: 'Đã lưu dữ liệu.',
                'redirect' => panel_route(module() . '.index'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($newPath && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }

            if ($newIconPath && Storage::disk('public')->exists($newIconPath)) {
                Storage::disk('public')->delete($newIconPath);
            }

            Log::error('Error updating ' . module() . ': ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'message' => 'Có lỗi xảy ra.',
            ], 500);
        }
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => "required|integer|exists:{$this->model->table},id",
            'items.*.position' => 'required|integer|min:0',
        ]);

        $items = $request->input('items', []);
        $idsInOrder = array_map(fn($i) => (int) $i['id'], $items);
        $categories = $this->model::whereIn('id', $idsInOrder)->get()->keyBy('id');

        $sorted = [];
        $added = [];

        while (count($sorted) < count($idsInOrder)) {
            $found = false;

            foreach ($idsInOrder as $id) {
                if (isset($added[$id])) {
                    continue;
                }

                $cat = $categories->get($id);

                if (!$cat) {
                    continue;
                }

                if ($cat->parent_id === null || isset($added[$cat->parent_id])) {
                    $sorted[] = $id;
                    $added[$id] = true;
                    $found = true;
                }
            }

            if (!$found) {
                break;
            }
        }

        DB::transaction(function () use ($sorted) {
            foreach ($sorted as $index => $id) {
                $this->model::where('id', $id)->update([
                    'order_position' => $index + 1,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thứ tự thành công.',
        ]);
    }

    public function updateOrder(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ["required", "integer", "exists:{$this->model->table},id"],
            'items.*.order_position' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['items'] as $row) {
                $this->model::where('id', $row['id'])->update([
                    'order_position' => $row['order_position'],
                ]);
            }
        });

        return response()->json([
            'message' => 'Cập nhật thứ tự danh mục thành công.',
        ]);
    }

    public function toggleStatus(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);
        $item->status = (int) $item->status === 1 ? 0 : 1;
        $item->save();

        return response()->json([
            'success' => true,
            'status' => (int) $item->status,
        ]);
    }

    public function destroy(Request $request, $domain, $id)
    {
        try {
            $row = $this->model->findOrFail($id);

            $hasChildren = $this->model::where('parent_id', $row->id)->exists();

            if ($hasChildren) {
                return response()->json([
                    'message' => 'Không thể xoá vì vẫn còn mục con.',
                ], 422);
            }

            if (!empty($row->image)) {
                $img = $row->image;
                $path = str_contains($img, '/') ? $img : ('uploads/' . module() . '/' . $img);
                Storage::disk('public')->delete($path);
            }

            if (!empty($row->icon)) {
                $icon = $row->icon;
                $iconPath = str_contains($icon, '/') ? $icon : ('uploads/' . module() . '/' . $icon);
                Storage::disk('public')->delete($iconPath);
            }

            $row->delete();

            return request()->ajax() || request()->wantsJson()
                ? response()->json(['message' => 'Xoá thành công.'])
                : redirect()->to(panel_route(module() . '.index'))->with('success', 'Xoá thành công.');
        } catch (\Throwable $e) {
            Log::error('Delete ' . module() . ' error: ' . $e->getMessage());

            $msg = 'Có lỗi xảy ra khi xoá.';

            return request()->ajax() || request()->wantsJson()
                ? response()->json(['message' => $msg], 500)
                : redirect()->back()->with('error', $msg);
        }
    }

    public function toggleHome(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);

        $item->home = (int) $item->home === 1 ? 0 : 1;

        $item->save();

        return response()->json([
            'success' => true,
            'home' => (int) $item->home,
        ]);
    }
}
