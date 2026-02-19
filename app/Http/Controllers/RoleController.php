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
            $roles = $this->roleRepository->getAll();
            return RoleResource::collection($roles);
        }

        return view('role.index');
    }

    public function create()
    {
        $this->authorize('roles.create');

        $permissions = Permission::all()
            ->groupBy(function ($permission) {
                return explode('.', $permission->name)[0]; // prefix sebelum titik
            });

        return view('role.create', compact('permissions'));
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

        $permissions = Permission::all()
            ->groupBy(function ($permission) {
                return explode('.', $permission->name)[0];
            });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('role.edit', compact('role', 'permissions', 'rolePermissions'));
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
}
