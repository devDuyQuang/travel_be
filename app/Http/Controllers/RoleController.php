<?php

// namespace App\Http\Controllers;

// use App\Models\Role;
// use App\Models\Permission;
// use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;
// use Illuminate\Support\Facades\DB;

// class RoleController extends Controller
// {
//     public function index()
//     {
//         $roles = Role::query()->latest()->get();

//         return view('role.index', compact('roles'));
//     }

//     public function create()
//     {
//         $permissions = Permission::query()
//             ->where('status', 1)
//             ->orderBy('code')
//             ->get()
//             ->groupBy(fn($permission) => explode('.', $permission->code)[0] ?? 'other');

//         return view('role.create', compact('permissions'));
//     }

//     public function store(Request $request)
//     {
//         $data = $request->validate([
//             'name' => ['required', 'string', 'max:255'],
//             'code' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:roles,code'],
//             'description' => ['nullable', 'string'],
//             'status' => ['nullable', 'boolean'],
//             'permission_ids' => ['nullable', 'array'],
//             'permission_ids.*' => ['integer', 'exists:permissions,id'],
//         ]);

//         DB::transaction(function () use ($data) {
//             $role = Role::create([
//                 'name' => $data['name'],
//                 'code' => $data['code'],
//                 'description' => $data['description'] ?? null,
//                 'status' => $data['status'] ?? 0,
//             ]);

//             $role->permissions()->sync($data['permission_ids'] ?? []);
//         });

//         return redirect()
//             ->route('role.index')
//             ->with('success', 'Tạo vai trò thành công.');
//     }

//     public function edit($id)
//     {
//         $item = Role::with('permissions')->findOrFail($id);

//         $permissions = Permission::query()
//             ->where('status', 1)
//             ->orderBy('code')
//             ->get()
//             ->groupBy(fn($permission) => explode('.', $permission->code)[0] ?? 'other');

//         $selectedPermissionIds = $item->permissions->pluck('id')->toArray();

//         return view('role.edit', compact('item', 'permissions', 'selectedPermissionIds'));
//     }

//     public function update(Request $request, $id)
//     {
//         $role = Role::findOrFail($id);

//         $data = $request->validate([
//             'name' => ['required', 'string', 'max:255'],
//             'code' => [
//                 'required',
//                 'string',
//                 'max:100',
//                 'alpha_dash',
//                 Rule::unique('roles', 'code')->ignore($role->id),
//             ],
//             'description' => ['nullable', 'string'],
//             'status' => ['nullable', 'boolean'],
//             'permission_ids' => ['nullable', 'array'],
//             'permission_ids.*' => ['integer', 'exists:permissions,id'],
//         ]);

//         DB::transaction(function () use ($role, $data) {
//             $role->update([
//                 'name' => $data['name'],
//                 'code' => $data['code'],
//                 'description' => $data['description'] ?? null,
//                 'status' => $data['status'] ?? 0,
//             ]);

//             $role->permissions()->sync($data['permission_ids'] ?? []);
//         });

//         return redirect()
//             ->route('role.index')
//             ->with('success', 'Cập nhật vai trò thành công.');
//     }

//     public function destroy($id)
//     {
//         $role = Role::findOrFail($id);

//         if ($role->code === 'admin') {
//             return back()->with('error', 'Không thể xoá vai trò Admin.');
//         }

//         $role->permissions()->detach();
//         $role->delete();

//         return redirect()
//             ->route('role.index')
//             ->with('success', 'Xoá vai trò thành công.');
//     }
// }




namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        return view('role.index');
    }

    public function datatable(Request $request)
    {
        $perPage = (int) $request->get('length', 25);
        $start   = (int) $request->get('start', 0);
        $page    = ($start / $perPage) + 1;

        $query = Role::query()
            ->select(['id', 'name', 'code', 'created_at'])
            ->orderByDesc('id');

        $recordsTotal = Role::query()->count();

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        $items = $query->forPage($page, $perPage)->get();

        return response()->json([
            'draw'            => (int) $request->get('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $items->map(fn($role) => [
                'id'         => $role->id,
                'name'       => $role->name,
                'code'       => $role->code,
                'created_at' => optional($role->created_at)->format('d/m/Y H:i'),
                '__details'  => '',
            ]),
        ]);
    }

    public function create()
    {
        return view('role.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:roles,code'],
        ]);

        Role::create($data);

        return redirect()
            ->to(panel_route('role.index'))
            ->with('success', 'Tạo vai trò thành công.');
    }

    public function edit(Request $request, $domain, $id)
    {
        $item = Role::findOrFail($id);

        return view('role.edit', compact('item'));
    }

    public function update(Request $request, $domain, $id)
    {
        $role = Role::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('roles', 'code')->ignore($role->id),
            ],
        ]);

        $role->update($data);

        return redirect()
            ->to(panel_route('role.index'))
            ->with('success', 'Cập nhật vai trò thành công.');
    }

    public function destroy(Request $request, $domain, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->code === 'admin') {
            return response()->json([
                'message' => 'Không thể xoá vai trò Admin.',
            ], 422);
        }

        DB::transaction(function () use ($role) {
            $role->permissions()->detach();
            $role->delete();
        });

        return response()->json([
            'message' => 'Xoá vai trò thành công.',
        ]);
    }
}
