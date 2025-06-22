<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MaintenanceTypeController extends Controller
{
    public function index()
    {
        return view('tenant.admin.maintenance.types.index');
    }

    public function data(Request $request)
    {
        return DataTables::of(MaintenanceType::query())
            ->addColumn('acciones', function ($type) {
                $edit = route('maintenance.type.edit', $type);
                $delete = route('maintenance.type.destroy', $type);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return <<<HTML
                    <a href="{$edit}" class="btn btn-sm btn-outline-info"><i class="fas fa-pen"></i></a>
                    <form action="{$delete}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar este tipo de mantención?')">
                        {$csrf}{$method}
                        <button class="btn btn-sm btn-outline-info"><i class="fas fa-trash"></i></button>
                    </form>
                HTML;
            })
            ->rawColumns(['acciones'])
            ->make(true);
    }

    public function create()
    {
        return view('tenant.admin.maintenance.types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        MaintenanceType::create($data);

        return redirect()->route('maintenance.type.index')->with('success', 'Tipo de mantención creado.');
    }

    public function edit(MaintenanceType $type)
    {
        return view('tenant.admin.maintenance.types.edit', ['maintenanceType' => $type]);
    }

    public function update(Request $request, MaintenanceType $type)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $type->update($data);

        return redirect()->route('maintenance.type.index')->with('success', 'Tipo de mantención actualizado.');
    }

    public function destroy(MaintenanceType $type)
    {
        if ($type->maintenances()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar este tipo de mantención porque tiene mantenciones asociadas.');
        }

        $type->delete();

        return redirect()->route('maintenance.type.index')->with('success', 'Tipo de mantención eliminado correctamente.');
    }

}
