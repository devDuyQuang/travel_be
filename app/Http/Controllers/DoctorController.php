<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    private Doctor $model;

    public function __construct()
    {
        $this->model = new Doctor();
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

        $query = $this->model->with('user:id,name')->select([
            'id',
            'name',
            'image',
            'specialty',
            'birth_year',
            'phone',
            'linkedin',
            'facebook',
            'twitter',
            'youtube',
            'user_id',
            'created_at',
        ]);

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('specialty', 'like', "%{$searchValue}%")
                    ->orWhere('phone', 'like', "%{$searchValue}%")
                    ->orWhere('linkedin', 'like', "%{$searchValue}%")
                    ->orWhere('facebook', 'like', "%{$searchValue}%")
                    ->orWhere('twitter', 'like', "%{$searchValue}%")
                    ->orWhere('youtube', 'like', "%{$searchValue}%");
            });
        }

        $recordsTotal = $this->model->count();
        $recordsFiltered = !empty($searchValue) ? (clone $query)->count() : $recordsTotal;

        $items = (clone $query)
            ->orderByDesc('id')
            ->offset($start)
            ->limit($length)
            ->get();

        $rows = $items->map(function (Doctor $item) {
            $socials = array_filter([
                'linkedin' => $item->linkedin,
                'facebook' => $item->facebook,
                'twitter' => $item->twitter,
                'youtube' => $item->youtube,
            ], fn ($value) => !empty($value));

            return [
                'id' => $item->id,
                'name' => $item->name,
                'image' => function_exists('normalize_image_url')
                    ? normalize_image_url($item->image, module())
                    : ($item->image ? Storage::url($item->image) : null),
                'specialty' => $item->specialty,
                'birth_year' => $item->birth_year,
                'phone' => $item->phone,
                'socials' => $socials,
                'meta' => '',
                'linkedin' => $item->linkedin,
                'facebook' => $item->facebook,
                'twitter' => $item->twitter,
                'youtube' => $item->youtube,
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
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'birth_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'phone' => ['nullable', 'string', 'max:50'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
        ]);

        $newPath = null;

        try {
            if ($request->hasFile('image')) {
                $domainSlug = $this->sanitizeDomain($request->route('domain') ?? 'default');
                $uploadDir = "uploads/{$domainSlug}/" . (module() ?? 'doctor');
                $filename = Str::slug($data['name']) . '_' . time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                $newPath = $request->file('image')->storeAs($uploadDir, $filename, 'public');
                $data['image'] = $newPath;
            }

            $data['user_id'] = Auth::id();

            $this->model->create($data);

            $msg = __('messages.data_saved') ?: 'Thêm mới thành công';

            return $request->wantsJson()
                ? response()->json(['message' => $msg, 'redirect_url' => panel_route(module() . '.index')])
                : redirect()->to(panel_route(module() . '.index'))->with('success', $msg);
        } catch (\Throwable $e) {
            if ($newPath && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }

            Log::error('Create doctor error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['message' => __('messages.unexpected_error')], 500);
        }
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
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'birth_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'phone' => ['nullable', 'string', 'max:50'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
        ]);

        $oldPath = $item->image;
        $newPath = null;
        $wantRemove = $request->boolean('remove_image');

        try {
            if ($request->hasFile('image')) {
                $domainSlug = $this->sanitizeDomain($domain ?? 'default');
                $uploadDir = "uploads/{$domainSlug}/" . (module() ?? 'doctor');
                $filename = Str::slug($data['name']) . '_' . time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                $newPath = $request->file('image')->storeAs($uploadDir, $filename, 'public');
                $data['image'] = $newPath;
            } elseif ($wantRemove) {
                $data['image'] = null;
            }

            $item->update($data);

            if ($oldPath && ($newPath || $wantRemove) && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $msg = __('messages.data_saved') ?: 'Cập nhật thành công';

            return $request->wantsJson()
                ? response()->json(['message' => $msg, 'redirect_url' => null])
                : redirect()->back()->with('success', $msg);
        } catch (\Throwable $e) {
            if ($newPath && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }

            Log::error('Update doctor error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['message' => __('messages.unexpected_error')], 500);
        }
    }

    public function destroy(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);

        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return $request->wantsJson()
            ? response()->json(['message' => 'Xóa thành công'])
            : redirect()->to(panel_route(module() . '.index'))->with('success', 'Xóa thành công');
    }

    private function sanitizeDomain(string $domain): string
    {
        return preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($domain)) ?: 'default';
    }
}
