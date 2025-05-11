<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use Yajra\DataTables\Facades\DataTables;

class BankController extends Controller
{
    /** Mostrar listado principal de bancos */
    public function index()
    {
        return view('tenant.admin.maintainer.bank.index');
    }

    /** Obtener datos para DataTables */
    public function data()
    {
        $banks = Bank::select(['id_bank', 'name_bank', 'created_at', 'updated_at']);

        return DataTables::of($banks)
            ->addColumn('action', function ($bank) {
                $editUrl = route('banco.edit', $bank->id_bank);
                $deleteUrl = route('banco.destroy', $bank->id_bank);

                return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-warning me-1">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /** Mostrar formulario de creación */
    public function create()
    {
        return view('tenant.admin.maintainer.bank.create');
    }

    /** Guardar nuevo banco */
    public function store(Request $request)
    {
        $request->validate([
            'name_bank' => 'required|string|max:100|unique:banks,name_bank',
        ]);

        Bank::create($request->only('name_bank'));

        return redirect()->route('banco.index')->with('success', 'Banco creado correctamente.');
    }

    /** Mostrar formulario de edición */
    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        return view('tenant.admin.maintainer.bank.edit', compact('bank'));
    }

    /** Actualizar banco existente */
    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);

        $request->validate([
            'name_bank' => 'required|string|max:100|unique:banks,name_bank,' . $bank->id_bank . ',id_bank',
        ]);

        $bank->update($request->only('name_bank'));

        return redirect()->route('banco.index')->with('success', 'Banco actualizado correctamente.');
    }

    /** Eliminar banco */
    public function destroy($id)
    {
        Bank::destroy($id);
        return redirect()->route('banco.index')->with('success', 'Banco eliminado correctamente.');
    }
}
