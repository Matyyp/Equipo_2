<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BranchOffice;
use Illuminate\Support\Facades\Hash;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $sucursalId = $request->get('id_sucursal');

        $sucursal = null;
        if ($sucursalId) {
            $sucursal = BranchOffice::find($sucursalId);
            if (!$sucursal) {
                abort(404, 'Sucursal no encontrada');
            }
        }

        return view('tenant.admin.maintainer.worker.create', compact('sucursal'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|confirmed|min:8',
            'role'              => 'required|exists:roles,name',
            'id_branch_office'  => 'nullable|exists:branch_offices,id_branch', // permite null si es SuperAdmin
        ]);

        
        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => Hash::make($data['password']),
            'id_branch_office'  => $data['id_branch_office'] ?? null,
        ]);

        
        $user->assignRole($data['role']);

        
        return to_route('trabajadores.show', $data['id_branch_office'])
            ->with('success', 'Usuario creado correctamente.');
    }


    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        $sucursal = BranchOffice::find($id);

        if (!$sucursal) {
            abort(404, 'Sucursal no encontrada');
        }

        if (request()->ajax()) {
            $users = User::with('roles')
                ->where('id_branch_office', $sucursal->id_branch)
                ->whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'SuperAdmin');
                })
                ->get();

            return datatables()->of($users)
                ->addColumn('rol', fn($user) => $user->roles->pluck('name')->implode(', '))
                ->editColumn('phone', fn($user) => $user->phone ?? 'No hay dato')
                ->editColumn('created_at', fn($user) => $user->created_at->format('d/m/Y'))
                ->addColumn('acciones', function ($user) {
                    $editUrl = route('trabajadores.edit', $user->id);
                    $deleteUrl = route('trabajadores.destroy', $user->id);

                    return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-outline-secondary me-1" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                        <form action="' . $deleteUrl . '" method="POST" class="d-inline delete-form">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    ';
                })
                ->rawColumns(['acciones']) // Importante para renderizar HTML
                ->make(true);
        }


        return view('tenant.admin.maintainer.worker.show', [
            'sucursal' => $sucursal
        ]);
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $branchs = BranchOffice::all();

        return view('tenant.admin.maintainer.worker.edit', compact('user', 'branchs'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
            'id_branch_office' => 'required|exists:branch_offices,id_branch',
        ]);

        // Actualiza los datos del usuario
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'id_branch_office' => $request->id_branch_office,
        ]);

        // Actualiza su rol
        $user->syncRoles([$request->role]);

        return redirect()->route('trabajadores.show', $request->id_branch_office)
                        ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Trabajador eliminado correctamente.']);
        }

        return redirect()->route('trabajadores.show', $user->id_branch_office)
                        ->with('success', 'Trabajador eliminado correctamente.');
    }
}
