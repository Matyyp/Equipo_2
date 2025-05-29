<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Region;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{
    /**
     * Muestra el listado de ubicaciones o devuelve datos para DataTables.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $locations = Location::with('location_region')->select('locations.*');

            return DataTables::of($locations)
                ->addColumn('region', function ($location) {
                    return $location->location_region->name_region ?? '-';
                })
                ->addColumn('action', function ($location) {
                    $editUrl = route('locacion.edit', $location->id_location);
                    $deleteUrl = route('locacion.destroy', $location->id_location);
                
                    return '
                        <a href="' . $editUrl . '" class="btn btn-outline-info btn-sm text-info" title="Editar">
                        <i class="fas fa-pen"></i>
                        </a>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('tenant.admin.maintainer.location.index');
    }

    /**
     * Muestra el formulario para crear una nueva ubicación.
     */
    public function create()
    {
        $region = Region::all();
        return view('tenant.admin.maintainer.location.create', compact('region'));
    }

    /**
     * Almacena una nueva ubicación en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'region' => 'required|exists:regions,id',
            'commune' => 'required|string|max:255',
        ]);

        Location::create([
            'id_region' => $request->region,
            'commune' => $request->commune,
        ]);

        return redirect()->route('locacion.index')->with('success', 'Ubicación creada correctamente.');
    }

    /**
     * Muestra el formulario para editar una ubicación existente.
     */
    public function edit(string $id)
    {
        $location = Location::with('location_region')->findOrFail($id);
        $region = Region::all();

        return view('tenant.admin.maintainer.location.edit', compact('location', 'region'));
    }

    /**
     * Actualiza una ubicación existente en la base de datos.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'region' => 'required|exists:regions,id',
            'commune' => 'required|string|max:255',
        ]);

        $location = Location::findOrFail($id);
        $location->update([
            'id_region' => $request->region,
            'commune' => $request->commune,
        ]);

        return redirect()->route('locacion.index')->with('success', 'Ubicación actualizada correctamente.');
    }

    /**
     * Elimina una ubicación de la base de datos.
     */
    public function destroy(string $id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return redirect()->route('locacion.index')->with('success', 'Ubicación eliminada correctamente.');
    }
}
