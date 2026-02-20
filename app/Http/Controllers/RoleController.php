<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RoleRepository;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct(
        protected RoleRepository $roleRepository
    ) {}

    public function index()
    {
        $this->authorize('roles.view');

        if (request()->expectsJson()) {

            $filter = request('filter', 'active');

            $roles = $this->roleRepository->getAll($filter);

            return RoleResource::collection($roles);
        }

        return view('role.index');
    }

    public function create()
    {
        $this->authorize('roles.create');

        $menus = \App\Models\Menu::with([
            'children.permissions',
            'permissions'
        ])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('role.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $this->authorize('roles.create');

        $validated = $request->validate([
            'name'        => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name'       => $validated['name'],
            'guard_name' => 'web'
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return response()->json(['message' => 'Role created']);
    }


    public function show(string $id)
    {
        $this->authorize('roles.read');
        //
    }
    public function edit(string $id)
    {
        $this->authorize('roles.update');

        $role = Role::with('permissions')->findOrFail($id);

        $menus = \App\Models\Menu::with([
            'children.permissions',
            'permissions'
        ])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('role.edit', compact('role', 'menus', 'rolePermissions'));
    }

    public function update(Request $request, string $id)
    {
        $this->authorize('roles.update');

        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update([
            'name' => $validated['name']
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return response()->json(['message' => 'Role updated']);
    }

    public function destroy(Role $role)
    {
        $this->authorize('roles.delete');

        $role->delete();

        return response()->noContent();
    }

    public function restore($id)
    {
        $this->authorize('roles.restore');

        $role = Role::onlyTrashed()->findOrFail($id);

        $role->restore();

        return response()->json([
            'message' => 'Role restored successfully'
        ]);
    }

    public function forceDelete($id)
    {
        $this->authorize('roles.forceDelete');

        $role = Role::onlyTrashed()->findOrFail($id);

        $role->forceDelete();

        return response()->json([
            'message' => 'Role permanently deleted'
        ]);
    }
}
