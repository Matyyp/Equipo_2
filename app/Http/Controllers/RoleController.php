<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        return view('tenant.admin.roles.index');
    }

    public function getData(Request $request)
    {
        $query = Role::withCount('permissions');
        return DataTables::eloquent($query)
            ->addColumn('permissions_count', fn(Role $r) => $r->permissions_count)
            ->addColumn('action', function(Role $r) {
                $html = '';
                if (auth()->user()->hasRole('SuperAdmin')) {
                    $html .= '<a href="'.route('roles.edit',$r).'" class="btn btn-outline-secondary btn-sm text-dark me-1">'
                        . '<i class="fas fa-pen"></i></a>';
                    $html .= '<form action="'.route('roles.destroy',$r).'" method="POST" style="display:inline">'
                        . csrf_field().method_field('DELETE')
                        . '<button onclick="return confirm(\'¿Eliminar rol '.$r->name.'?\')" '
                        . 'class="btn btn-outline-secondary btn-sm text-dark ml-1">'
                        . '<i class="fas fa-trash"></i></button></form>';
                }
                return $html;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create()
    {
        $groupedPermissions = config('permissions_ui');
        return view('tenant.admin.roles.create', compact('groupedPermissions'));
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
        $groupedPermissions = config('permissions_ui');
        
        // Obtener los permisos actuales del rol (solo los nombres)
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('tenant.admin.roles.edit', compact('role', 'groupedPermissions', 'rolePermissions'));
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
