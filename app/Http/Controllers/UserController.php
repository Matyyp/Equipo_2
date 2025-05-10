<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $query = User::with('roles')->select('users.*');

        return DataTables::eloquent($query)
            ->addColumn('role', fn(User $user) => $user->getRoleNames()->implode(', '))
            ->addColumn('action', function(User $user) {
                $buttons = '';
                if (auth()->user()->can('users.edit')) {
                    $buttons .= '<a href="'.route('users.edit', $user).'" '
                              . 'class="btn btn-sm btn-warning mr-1">'
                              . '<i class="fas fa-edit"></i></a>';
                }
                if (auth()->user()->can('users.delete')) {
                    $buttons .= '<form action="'.route('users.destroy', $user).'" '
                              . 'method="POST" style="display:inline">'
                              . csrf_field().method_field('DELETE')
                              . '<button onclick="return confirm(\'¿Eliminar este usuario?\')" '
                              . 'class="btn btn-sm btn-danger"><i class="fas fa-trash"></i>'
                              . '</button></form>';
                }
                return $buttons;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create()
    {
        return view('tenant.admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'id_branch_office' => '1',
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
        ]);

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
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
