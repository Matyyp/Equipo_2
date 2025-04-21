<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('permissions')->get();
        return view('tenant.admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('tenant.admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $data['name']]);

        if (! empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return to_route('roles.index')
               ->with('success', "Rol “{$role->name}” creado correctamente");
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('tenant.admin.roles.edit', compact('role','permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return to_route('roles.index')
               ->with('success',"Permisos actualizados para el rol {$role->name}");
    }
    public function destroy(Role $role)
    {
        $name = $role->name;
        $role->delete();

        return to_route('roles.index')
               ->with('success', "Rol «{$name}» eliminado correctamente");
    }
}
