<?php

namespace App\Http\Controllers;

use App\Support\PublicUrl;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Enums\MenuTargetType;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    private Menu $model;

    public function __construct()
    {
        $this->model = new Menu();
    }

    public function index()
    {
        return view('menu.main');
    }

    public function datatable(Request $request)
    {
        $draw = (int) $request->get('draw', 1);
        $search = $request->input('search.value', '');

        $query = Menu::query()
            ->select(
                'id',
                'name',
                'topic',
                'path',
                'part_id',
                'parent_id',
                'location',
                'status',
                'sort',
                'order_position',
                'created_at',
                'created_by'
            )
            ->with('creator:id,name');

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $total = Menu::count();

        $allItems = $query
            ->orderByRaw("
            CASE
                WHEN location = 'header' THEN 0
                WHEN location = 'footer' THEN 1
                ELSE 2
            END
        ")
            ->orderBy('order_position')
            ->orderBy('parent_id')
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        $filtered = $allItems->count();

        $byParent = $allItems->groupBy(fn($item) => $item->parent_id);
        $sorted = collect();
        $visited = [];

        $buildTree = function ($parentId, $depth) use (&$buildTree, $byParent, &$sorted, &$visited) {
            $children = $byParent->get($parentId, collect());

            if ($parentId === null) {
                $children = $children->sortBy(function ($c) {
                    $locOrder = $c->location === 'header' ? 0 : ($c->location === 'footer' ? 1 : 2);
                    return $locOrder * 100000 + (int) $c->order_position;
                })->values();
            } else {
                $children = $children->sortBy('order_position')->values();
            }

            foreach ($children as $child) {
                $childId = (int) $child->id;

                // Chống vòng lặp: item đã duyệt thì bỏ qua
                if (isset($visited[$childId])) {
                    continue;
                }

                // Chống dữ liệu lỗi: tự lấy chính nó làm cha
                if (!empty($child->parent_id) && (int) $child->parent_id === $childId) {
                    continue;
                }

                $visited[$childId] = true;

                $sorted->push([
                    'item' => $child,
                    'depth' => $depth,
                ]);

                $buildTree($childId, $depth + 1);
            }
        };

        $buildTree(null, 0);

        // Nếu có menu bị mồ côi parent_id không tồn tại, vẫn đưa ra cuối bảng để không mất dữ liệu
        $allItems->each(function ($item) use (&$visited, &$sorted) {
            $itemId = (int) $item->id;

            if (!isset($visited[$itemId])) {
                $visited[$itemId] = true;

                $sorted->push([
                    'item' => $item,
                    'depth' => 0,
                ]);
            }
        });

        $data = $sorted->map(function ($entry) {
            $c = $entry['item'];

            $publicUrl = PublicUrl::menu($c);

            return [
                'id' => $c->id,
                'drag_handle' => '',
                'name' => $c->name ?? '—',
                'depth' => $entry['depth'],
                'location' => $c->location,
                'status' => (int) $c->status,
                'part_id' => $c->part_id,
                'path' => $publicUrl,
                'created_at' => $c->created_at ? $c->created_at->format('d/m/Y H:i') : '',
                'creator_name' => $c->creator ? $c->creator->name : '—',
                'actions' => null,
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

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:menu,id',
            'items.*.position' => 'required|integer|min:0',
        ]);

        $items = $request->input('items', []);
        $idsInOrder = array_map(fn($i) => (int) $i['id'], $items);
        $menus = Menu::whereIn('id', $idsInOrder)->get()->keyBy('id');

        $sorted = [];
        $added = [];

        while (count($sorted) < count($idsInOrder)) {
            $found = false;

            foreach ($idsInOrder as $id) {
                if (isset($added[$id])) {
                    continue;
                }

                $m = $menus->get($id);

                if (!$m) {
                    continue;
                }

                if ($m->parent_id === null || isset($added[$m->parent_id])) {
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
                Menu::where('id', $id)->update([
                    'order_position' => $index + 1,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thứ tự thành công.',
        ]);
    }

    public function create()
    {
        $items = $this->model::select('id', 'name', 'parent_id')
            ->where('status', 1)
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        $parents = recursive($items);
        $locations = locations();
        $types = MenuTargetType::options();

        return view('menu.create', compact(
            'items',
            'parents',
            'locations',
            'types',
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validateMenuData($request);

        $resolved = $this->resolveTarget($data['topic'], (int) $data['part_id']);
        $payload = $this->buildMenuPayload($data, $resolved['path']);

        if (!isset($data['sort'])) {
            $payload['sort'] = (int) $this->model
                ->where('parent_id', $payload['parent_id'])
                ->max('sort') + 1;
        }

        $payload['order_position'] = (int) $this->model->max('order_position') + 1;
        $payload['created_by'] = Auth::id();

        $row = $this->model->create($payload);

        return request()->ajax() || request()->wantsJson()
            ? response()->json(['message' => 'Đã tạo menu.', 'id' => $row->id])
            : redirect()->to(panel_route('menu.index'))->with('success', 'Đã tạo menu.');
    }

    public function edit(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);

        $excludeIds = $this->descendantIds($item->id);
        $excludeIds[] = (int) $item->id;

        $candidates = $this->model::select('id', 'name', 'parent_id', 'status')
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

        $parents = recursive(items: $candidates, selected: $item->parent_id, exclude: $item->id);
        $locations = locations();
        $types = MenuTargetType::options();

        return view('menu.edit', compact(
            'item',
            'parents',
            'locations',
            'types',
        ));
    }

    public function update(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);
        $data = $this->validateMenuData($request);

        if (!empty($data['parent_id']) && (int) $data['parent_id'] === (int) $id) {
            return back()->withErrors([
                'parent_id' => 'Không thể chọn chính nó làm cha.',
            ])->withInput();
        }

        if (!empty($data['parent_id'])) {
            $descendantIds = $this->descendantIds($id);

            if (in_array((int) $data['parent_id'], $descendantIds, true)) {
                return back()->withErrors([
                    'parent_id' => 'Không thể chọn hậu duệ làm cha.',
                ])->withInput();
            }
        }

        $resolved = $this->resolveTarget($data['topic'], (int) $data['part_id']);
        $payload = $this->buildMenuPayload($data, $resolved['path'], $item);

        $item->fill($payload)->save();

        return request()->ajax() || request()->wantsJson()
            ? response()->json(['message' => 'Cập nhật thành công.'])
            : redirect()->to(panel_route('menu.index'))->with('success', 'Cập nhật thành công.');
    }
    public function targets(Request $request, $domain = null)
    {
        $topic = $request->get('topic', $request->get('type'));

        if (!in_array($topic, array_keys(MenuTargetType::options()), true)) {
            return response()->json([
                'success' => false,
                'message' => 'Loại menu không hợp lệ.',
                'data' => [],
            ], 422);
        }

        if ($topic === MenuTargetType::CATEGORY->value) {
            $categories = Category::query()
                ->where('status', 1)
                ->select(['id', 'name', 'slug', 'type', 'parent_id', 'sort', 'order_position'])
                ->orderByRaw('parent_id IS NOT NULL')
                ->orderBy('parent_id')
                ->orderBy('sort')
                ->orderBy('order_position')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $this->flattenCategoryTargets($categories),
            ]);
        }

        $items = Post::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    private function flattenCategoryTargets($categories): array
    {
        $byParent = $categories->groupBy(fn($item) => $item->parent_id ?? 0);
        $result = [];

        $walk = function ($parentId, int $depth) use (&$walk, $byParent, &$result) {
            $children = ($byParent[$parentId] ?? collect())
                ->sortBy([
                    ['sort', 'asc'],
                    ['order_position', 'asc'],
                    ['name', 'asc'],
                ])
                ->values();

            foreach ($children as $category) {
                $prefix = $depth > 0
                    ? str_repeat('    ', $depth - 1) . '└── '
                    : '';

                $result[] = [
                    'id' => $category->id,
                    'name' => $prefix . $category->name,
                    'slug' => $category->slug,
                    'type' => $category->type,
                    'path' => PublicUrl::category($category),
                ];

                $walk($category->id, $depth + 1);
            }
        };

        $walk(0, 0);

        return $result;
    }



    public function toggleStatus(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);

        $newStatus = (int) $item->status === 1 ? 0 : 1;

        DB::transaction(function () use ($item, $newStatus) {

            // update menu cha
            $item->update([
                'status' => $newStatus,
            ]);

            // update menu con
            Menu::where('parent_id', $item->id)
                ->update([
                    'status' => $newStatus,
                ]);
        });

        return response()->json([
            'success' => true,
            'status' => $newStatus,
        ]);
    }

    public function destroy(Request $request, $domain, $id)
    {
        $row = $this->model->findOrFail($id);

        if ($this->model->where('parent_id', $row->id)->exists()) {
            return response()->json([
                'message' => 'Không thể xoá vì vẫn còn mục con.',
            ], 422);
        }

        $row->delete();

        return request()->ajax() || request()->wantsJson()
            ? response()->json(['message' => 'Xoá thành công.'])
            : redirect()->to(panel_route('menu.index'))->with('success', 'Xoá thành công.');
    }

    public function updateOrder(Request $request)
    {
        $payload = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', Rule::exists('menu', 'id')],
            'items.*.parent_id' => ['nullable', 'integer', Rule::exists('menu', 'id')],
            'items.*.sort' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($payload) {
            foreach ($payload['items'] as $row) {
                $this->model->where('id', $row['id'])->update([
                    'parent_id' => $row['parent_id'] ?? null,
                    'sort' => (int) $row['sort'],
                    'updated_by' => Auth::id(),
                ]);
            }
        });

        return response()->json([
            'message' => 'Cập nhật thứ tự thành công.',
        ]);
    }

    private function descendantIds(int $rootId): array
    {
        $ids = [];
        $queue = [$rootId];
        $visited = [$rootId => true];

        while (!empty($queue)) {
            $children = $this->model
                ->whereIn('parent_id', $queue)
                ->where('id', '!=', $rootId)
                ->pluck('id')
                ->all();

            $queue = [];

            foreach ($children as $cid) {
                $cid = (int) $cid;

                if (isset($visited[$cid])) {
                    continue;
                }

                $visited[$cid] = true;
                $ids[] = $cid;
                $queue[] = $cid;
            }
        }

        return $ids;
    }

    private function validateMenuData(Request $request): array
    {
        $request->merge([
            'parent_id' => filled($request->parent_id) && is_numeric($request->parent_id)
                ? (int) $request->parent_id
                : null,
        ]);

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'topic' => ['required', Rule::in(array_keys(MenuTargetType::options()))],
            'part_id' => ['required', 'integer'],
            'parent_id' => ['nullable', 'integer', Rule::exists('menu', 'id')],
            'location' => ['nullable', 'string', 'max:50'],
            'sort' => ['nullable', 'integer', 'min:0'],
        ]);
    }
    private function resolveTarget(string $topic, int $partId): array
    {
        return match ($topic) {
            MenuTargetType::CATEGORY->value => (function () use ($partId) {
                $target = Category::query()
                    ->where('status', 1)
                    ->findOrFail($partId);

                return [
                    'target' => $target,
                    'path' => PublicUrl::category($target),
                ];
            })(),

            MenuTargetType::POST->value => (function () use ($partId) {
                $target = Post::query()
                    ->where('status', 1)
                    ->findOrFail($partId);

                return [
                    'target' => $target,
                    'path' => PublicUrl::post($target),
                ];
            })(),
        };
    }

    private function buildMenuPayload(array $data, string $path, ?Menu $item = null): array
    {
        return [
            'name' => $data['name'],
            'topic' => $data['topic'],
            'part_id' => $data['part_id'],
            'path' => $path,
            'parent_id' => $data['parent_id'] ?? null,
            'location' => $data['location'] ?? null,
            'status' => $item?->status ?? (!empty($path) ? 1 : 0),
            'sort' => isset($data['sort']) ? (int) $data['sort'] : ($item?->sort ?? 0),
            'updated_by' => Auth::id(),
        ];
    }
}
