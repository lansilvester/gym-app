<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::withCount('permissions')->latest()->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('create', Role::class);

        $permissions = Permission::orderBy('name')->get()->groupBy(fn($p) => explode('_', $p->name)[0] ?? 'other');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions(Permission::whereIn('id', $validated['permissions'])->get());
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::orderBy('name')->get()->groupBy(fn($p) => explode('_', $p->name)[0] ?? 'other');
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions(Permission::whereIn('id', $validated['permissions'] ?? [])->get());

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
