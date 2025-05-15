<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BranchOffice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    public function index()
    {
        return view('tenant.admin.users.index');
    }

    public function getData(Request $request)
    {
        $authUser = auth()->user();

        // Base query con relación a sucursal y roles
        $query = User::with(['roles', 'branch_office'])->select('users.*');

        // Si no es admin, limitar por sucursal
        if (!$authUser->hasRole('Admin')) {
            $query->where('id_branch_office', $authUser->id_branch_office);
        }

        return DataTables::eloquent($query)
            ->addColumn('role', fn(User $user) => $user->getRoleNames()->implode(', '))
            ->addColumn('sucursal', fn(User $user) => $user->branch_office->name_branch_offices ?? '<span class="text-muted">Todas las sucursales</span>')
            ->addColumn('action', function(User $user) {
                $protectedUserIds = [1];

                if (in_array($user->id, $protectedUserIds)) {
                    return '<span class="text-muted">Protegido</span>';
                }

                $buttons = '<div class="w-100 text-center">';

                if (auth()->user()->can('users.edit')) {
                    $buttons .= '
                        <a href="' . route('users.edit', $user) . '" 
                        class="btn btn-sm btn-outline-info mx-1" 
                        title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                    ';
                }

                if (auth()->user()->can('users.delete')) {
                    $buttons .= '
                        <form action="' . route('users.destroy', $user) . '" 
                            method="POST" 
                            onsubmit="return confirm(\'¿Eliminar este usuario?\')" 
                            style="display:inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" 
                                    class="btn btn-sm btn-outline-danger mx-1" 
                                    title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    ';
                }

                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['action', 'sucursal']) // marcar sucursal como raw si usas HTML
            ->toJson();
    }


    public function create()
    {
        $branchs=BranchOffice::all();
        return view('tenant.admin.users.create', compact('branchs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role'     => 'required|exists:roles,name',
            'id_branch_office' => 'required|exists:branch_offices,id_branch',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'id_branch_office' => $data['id_branch_office'],
        ]);

        $user->assignRole($data['role']);
        return to_route('users.index')->with('success', 'Usuario creado');
    }

    public function edit(User $user)
    {
        return view('tenant.admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|exists:roles,name',
            'id_branch_office' => 'required|exists:branch_offices,id_branch',
        ]);

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            'id_branch_office' => $data['branch_office_id'],
        ]);

        $user->syncRoles([$data['role']]);

        return to_route('users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return to_route('users.index')
               ->with('success', 'Usuario eliminado correctamente');
    }
}
