<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use App\Models\TypeAccount;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TypeAccountController extends Controller
{
    public function index()
    {
        return view('tenant.admin.maintainer.type_account.index');
    }

    public function data()
    {
        $types = TypeAccount::select(['id_type_account', 'name_type_account']);

        return DataTables::of($types)
            ->addColumn('action', function ($type) {
                $editUrl = route('tipo_cuenta.edit', $type->id_type_account);
                $deleteUrl = route('tipo_cuenta.destroy', $type->id_type_account);

                return '
                     <a href="' . $editUrl . '" class="btn btn-outline-secondary btn-sm text-dark" title="Editar">
                    <i class="fas fa-pen"></i>
                    </a>
                    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('tenant.admin.maintainer.type_account.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_type_account' => 'required|string|max:100|unique:type_accounts,name_type_account',
        ]);

        TypeAccount::create($request->only('name_type_account'));

        return redirect()->route('tipo_cuenta.index')->with('success', 'Tipo de cuenta creado correctamente.');
    }

    public function edit($id)
    {
        $type = TypeAccount::findOrFail($id);
        return view('tenant.admin.maintainer.type_account.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $type = TypeAccount::findOrFail($id);

        $request->validate([
            'name_type_account' => 'required|string|max:100|unique:type_accounts,name_type_account,' . $type->id_type_account . ',id_type_account',
        ]);

        $type->update($request->only('name_type_account'));

        return redirect()->route('tipo_cuenta.index')->with('success', 'Tipo de cuenta actualizado correctamente.');
    }

    public function destroy($id)
    {
        TypeAccount::destroy($id);
        return redirect()->route('tipo_cuenta.index')->with('success', 'Tipo de cuenta eliminado.');
    }
}
