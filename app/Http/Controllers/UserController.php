<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function index()
    {
        return view(module() . '.main');
    }

    public function datatable(Request $request)
    {
        $draw   = (int) $request->get('draw', 1);
        $start  = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 25);

        if ($length === -1) {
            $length = 100;
        }

        $searchValue = $request->input('search.value', '');

        $query = $this->model
            ->with('role')
            ->select([
                'id',
                'role_id',
                'name',
                'email',
                'created_at',
            ]);

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('email', 'like', "%{$searchValue}%");
            });
        }

        $recordsTotal = $this->model->count();
        $recordsFiltered = !empty($searchValue) ? $query->count() : $recordsTotal;

        $items = (clone $query)
            ->orderByDesc('id')
            ->offset($start)
            ->limit($length)
            ->get();

        $rows = $items->map(fn($u) => [
            'id'         => $u->id,
            'name'       => $u->name ?? '',
            'email'      => $u->email ?? '',
            'role'       => $u->role?->name ?? 'Chưa phân quyền',
            'created_at' => optional($u->created_at)->format('d/m/Y H:i'),
            'creator'    => '—',
            '__details'  => '',
        ])->values();

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $rows,
        ]);
    }

    public function create()
    {
        $roles = Role::where('status', 1)->orderBy('name')->get();

        return view(module() . '.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $table = $this->model->getTable();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', "unique:{$table},email"],
            'password' => ['required', 'string', 'min:8'],
            'role_id'  => ['nullable', 'exists:roles,id'],
        ]);

        $this->model->create($data);

        return $request->wantsJson()
            ? response()->json(['message' => 'Tạo người dùng thành công.'])
            : redirect()->to(panel_route(module() . '.index'))->with('success', 'Tạo người dùng thành công.');
    }

    public function edit(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);
        $roles = Role::where('status', 1)->orderBy('name')->get();

        return view(module() . '.edit', compact('item', 'roles'));
    }

    public function update(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);
        $table = $this->model->getTable();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique($table, 'email')->ignore($item->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role_id'  => ['nullable', 'exists:roles,id'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $item->fill($data)->save();

        return $request->wantsJson()
            ? response()->json(['message' => 'Cập nhật người dùng thành công.'])
            : redirect()->to(panel_route(module() . '.index'))->with('success', 'Cập nhật người dùng thành công.');
    }

    public function destroy(Request $request, $domain, $id)
    {
        $user = $this->model->findOrFail($id);

        if (auth()->check() && auth()->id() === $user->id) {
            $msg = 'Bạn không thể tự xoá chính mình.';

            return $request->wantsJson()
                ? response()->json(['message' => $msg], 422)
                : redirect()->to(panel_route(module() . '.index'))->with('error', $msg);
        }

        $user->delete();

        return $request->wantsJson()
            ? response()->json(['message' => 'Xoá người dùng thành công.'])
            : redirect()->to(panel_route(module() . '.index'))->with('success', 'Xoá người dùng thành công.');
    }
}
