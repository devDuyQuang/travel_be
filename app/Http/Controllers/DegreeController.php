<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DegreeController extends Controller
{
    private Degree $model;

    public function __construct()
    {
        $this->model = new Degree();
    }

    public function index()
    {
        return view(module() . '.main');
    }

    public function datatable(Request $request)
    {
        $draw = (int) $request->get('draw', 1);
        $start = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 25);

        if ($length === -1) {
            $length = 100;
        }

        $searchValue = trim((string) $request->input('search.value', ''));

        $query = $this->model->with('user')->select([
            'id',
            'name',
            'image',
            'description',
            'link_text',
            'year',
            'user_id',
            'created_at',
        ]);

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('description', 'like', "%{$searchValue}%")
                    ->orWhere('link_text', 'like', "%{$searchValue}%")
                    ->orWhere('year', 'like', "%{$searchValue}%");
            });
        }

        $recordsTotal = $this->model->count();
        $recordsFiltered = $searchValue !== '' ? (clone $query)->count() : $recordsTotal;

        $items = (clone $query)
            ->orderByDesc('id')
            ->offset($start)
            ->limit($length)
            ->get();

        $rows = $items->map(function (Degree $item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'image' => $item->image ? Storage::url($item->image) : null,
                'description' => Str::limit((string) $item->description, 50),
                'link_text' => $item->link_text,
                'year' => $item->year,
                'creator' => optional($item->user)->name ?? '—',
                'created_at' => $item->created_at ? $item->created_at->toISOString() : null,
                '__details' => '',
            ];
        })->values();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows,
        ]);
    }

    public function create()
    {
        return view(module() . '.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'description' => 'nullable|string',
            'link_text' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:10',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('degrees', 'public');
        }

        $data['user_id'] = auth()->id();

        $this->model->create($data);

        $msg = __('messages.data_saved') ?: 'Thêm mới thành công';

        return $request->wantsJson()
            ? response()->json([
                'message' => $msg,
                'redirect_url' => panel_route(module() . '.index'),
            ])
            : redirect()->to(panel_route(module() . '.index'))->with('success', $msg);
    }

    public function edit(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);

        $currentImageUrl = function_exists('normalize_image_url')
            ? normalize_image_url($item->image, module())
            : ($item->image ? Storage::url($item->image) : null);

        return view(module() . '.edit', compact('item', 'currentImageUrl'));
    }

    public function update(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'description' => 'nullable|string',
            'link_text' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:10',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $data['image'] = $request->file('image')->store('degrees', 'public');
        }

        $item->update($data);

        $msg = __('messages.data_saved') ?: 'Cập nhật thành công';

        return $request->wantsJson()
            ? response()->json([
                'message' => $msg,
                'redirect_url' => null,
            ])
            : redirect()->back()->with('success', $msg);
    }

    public function destroy(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return $request->wantsJson()
            ? response()->json(['message' => 'Xóa thành công'])
            : redirect()->to(panel_route(module() . '.index'))->with('success', 'Xóa thành công');
    }
}
