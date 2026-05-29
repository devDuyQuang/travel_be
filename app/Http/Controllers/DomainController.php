<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DomainController extends Controller
{
    private Domain $model;

    public function __construct()
    {
        $this->model = new Domain();
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

        $query = $this->model->with('creator:id,name')->select([
            'id',
            'name',
            'type',
            'status',
            'created_at',
            'created_by',
        ]);

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('type', 'like', "%{$searchValue}%");
            });
        }

        $recordsTotal = $this->model->count();
        $recordsFiltered = $searchValue !== '' ? (clone $query)->count() : $recordsTotal;

        $items = (clone $query)
            ->orderByDesc('id')
            ->offset($start)
            ->limit($length)
            ->get();

        $rows = $items->map(function (Domain $item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'status' => (int) $item->status,
                'creator' => optional($item->creator)->name ?? '—',
                'created_at' => $item->created_at ? $item->created_at->toISOString() : null,
                '__details' => '',
                'actions' => null,
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
        $data = $request->validate($this->rules());

        try {
            $payload = $this->normalizePayload($data);
            $payload['status'] = 1;

            if (auth()->check()) {
                $payload['created_by'] = auth()->id();
                $payload['updated_by'] = auth()->id();
            }

            $this->model->create($payload);

            return response()->json([
                'message' => __('messages.data_saved') ?: 'Thêm mới thành công.',
                'redirect_url' => panel_route(module() . '.index'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Create domain error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message' => __('messages.unexpected_error') ?: 'Có lỗi xảy ra.',
            ], 500);
        }
    }

    public function edit(Request $request, $tenantDomain, $id)
    {
        $item = $this->model->findOrFail($id);

        return view(module() . '.edit', compact('item'));
    }

    public function update(Request $request, $tenantDomain, $id)
    {
        $item = $this->model->findOrFail($id);
        $data = $request->validate($this->rules($id));

        try {
            $payload = $this->normalizePayload($data);
            $payload['status'] = $item->status;

            if (auth()->check()) {
                $payload['updated_by'] = auth()->id();
            }

            $item->update($payload);

            return response()->json([
                'message' => __('messages.data_saved') ?: 'Cập nhật thành công.',
                'redirect_url' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Update domain error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message' => __('messages.unexpected_error') ?: 'Có lỗi xảy ra.',
            ], 500);
        }
    }

    public function toggleStatus(Request $request, $tenantDomain, $id)
    {
        $item = $this->model->findOrFail($id);
        $item->status = (int) $item->status === 1 ? 0 : 1;
        $item->save();

        return response()->json([
            'success' => true,
            'status' => (int) $item->status,
        ]);
    }

    public function destroy(Request $request, $tenantDomain, $id)
    {
        try {
            $item = $this->model->findOrFail($id);
            $item->delete();

            return response()->json(['message' => 'Xoá thành công.']);
        } catch (\Throwable $e) {
            Log::error('Delete domain error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['message' => 'Có lỗi xảy ra khi xoá.'], 500);
        }
    }

    private function rules(?int $id = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique($this->model->getTable(), 'name')->ignore($id),
                function ($attribute, $value, $fail) {
                    $domain = strtolower(trim((string) $value));

                    if (str_starts_with($domain, 'http://') || str_starts_with($domain, 'https://')) {
                        $fail('Tên domain chỉ nhập hostname, không kèm giao thức.');
                        return;
                    }

                    if (!filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) || !str_contains($domain, '.')) {
                        $fail('Tên domain không đúng định dạng.');
                    }
                },
            ],
            'type' => ['required', Rule::in(['clinic', 'RAC'])],
        ];
    }

    private function normalizePayload(array $data): array
    {
        return [
            'name' => strtolower(trim($data['name'])),
            'type' => trim($data['type']),
        ];
    }
}
