<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MapController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->data(); // Usamos el método data() para AJAX
        }

        // Para la carga inicial sin AJAX (opcional)
        $maps = Map::all();
        
        return view('tenant.admin.landing.map.index', compact('maps'));
    }

    public function create()
    {
        return view('tenant.admin.landing.map.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'contactos' => 'required|string',
            'horario' => 'required|string',
            'texto_boton' => 'required|string|max:255',
            'coordenadas_mapa' => 'required|string',
            'url_boton' => 'nullable|url',
            'map_active' => 'boolean',
            'titulo_active' => 'boolean',
            'ciudad_active' => 'boolean',
            'direccion_active' => 'boolean',
            'contactos_active' => 'boolean',
            'horario_active' => 'boolean',
            'boton_active' => 'boolean',
            'boton_color_texto' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'boton_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'color_tarjeta' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'color_texto_tarjeta' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'color_mapa' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        Map::create($validated);

        return redirect()->route('landing.map.index')
            ->with('success', 'Mapa creado exitosamente.');
    }

    public function edit(Map $map)
    {
        return view('tenant.admin.landing.map.edit', compact('map'));
    }

    public function update(Request $request, Map $map)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'contactos' => 'required|string',
            'horario' => 'required|string',
            'texto_boton' => 'required|string|max:255',
            'coordenadas_mapa' => 'required|string',
            'url_boton' => 'nullable|url',
            'map_active' => 'boolean',
            'titulo_active' => 'boolean',
            'ciudad_active' => 'boolean',
            'direccion_active' => 'boolean',
            'contactos_active' => 'boolean',
            'horario_active' => 'boolean',
            'boton_active' => 'boolean',
            'boton_color_texto' => 'required|string',
            'boton_color' => 'required|string',
            'color_tarjeta' => 'required|string',
            'color_texto_tarjeta' => 'required|string',
            'color_mapa' => 'required|string',
        ]);

        $map->update($validated);

        return redirect()->route('landing.map.index')
            ->with('success', 'Mapa actualizado exitosamente.');
    }

    public function destroy(Map $map)
    {
        $map->delete();

        return redirect()->route('landing.map.index')
            ->with('success', 'Mapa eliminado exitosamente.');
    }

    // Método para obtener datos para DataTables
    public function data()
    {
        return DataTables::of(Map::query())
            ->addColumn('action', function($map) {
                return view('partials.actions', [
                    'edit' => route('landing.map.edit', $map->id_map),
                    'delete' => route('landing.map.destroy', $map->id_map)
                ]);
            })
            ->addColumn('status', function($map) {
                return $map->map_active ? 
                    '<span class="badge badge-success">Activo</span>' : 
                    '<span class="badge badge-danger">Inactivo</span>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}