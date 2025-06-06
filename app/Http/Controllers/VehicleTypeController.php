<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use App\Models\VehicleTypeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class VehicleTypeController extends Controller
{
    public function index()
    {
        return view('tenant.admin.landing.vehicle.index');
    }
public function data(Request $request)
{
    return DataTables::of(VehicleType::with('image'))
        ->addColumn('image', fn($v) => $v->image ? '<img src="' . tenant_asset($v->image->path) . '" width="100"/>' : '')
        
        ->addColumn('card_title', function($v) {
            $estado = $v->card_title_active
                ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>';
            return $estado . '<div class="mt-2 small">' . e(Str::limit($v->card_title, 50)) . '</div>';
        })

        ->addColumn('card_subtitle', function($v) {
            $estado = $v->card_subtitle_active
                ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>';
            return $estado . '<div class="mt-2 small">' . e(Str::limit($v->card_subtitle, 50)) . '</div>';
        })

        ->addColumn('colores', function($v) {
            return '
                <div class="d-flex flex-column gap-1 small">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:15px;height:15px;background-color:' . $v->card_background_color . ';border-radius:50%;border:1px solid #ccc;"></span> Fondo Tarjeta
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:15px;height:15px;background-color:' . $v->text_color . ';border-radius:50%;border:1px solid #ccc;"></span> Texto
                    </div>
                </div>
            ';
        })

        ->addColumn('acciones', function ($v) {
            $edit = route('landing.vehicle.edit', $v);
            $delete = route('landing.vehicle.destroy', $v);
            $csrf = csrf_field();
            $method = method_field('DELETE');
            return <<<HTML
                <a href="{$edit}" class="btn btn-outline-info btn-sm text-info me-1"><i class="fas fa-pen"></i></a>
                <form action="{$delete}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar esta tarjeta?')">
                    {$csrf}{$method}
                    <button class="btn btn-outline-info btn-sm text-info"><i class="fas fa-trash"></i></button>
                </form>
            HTML;
        })

        ->rawColumns(['image', 'card_title', 'card_subtitle', 'colores', 'acciones'])
        ->make(true);
}


    public function create()
    {
        return view('tenant.admin.landing.vehicle.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'card_title' => 'required|string|max:255',
            'card_title_active' => 'boolean',
            'card_subtitle' => 'nullable|string|max:255',
            'card_subtitle_active' => 'boolean',
            'text_color' => 'required|string|max:7',
            'card_background_color' => 'required|string|max:7',
            'image' => 'nullable|image|max:2048',
        ]);

        $vehicle = VehicleType::create($data);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store("landing", 'public');
            $vehicle->image()->create(['path' => $path]);
        }

        return redirect()->route('landing.vehicle.index')->with('success', 'Tarjeta creada.');
    }

    public function edit(VehicleType $vehicle)
    {
        return view('tenant.admin.landing.vehicle.edit', compact('vehicle'));
    }

    public function update(Request $request, VehicleType $vehicle)
    {
        $data = $request->validate([
            'card_title' => 'required|string|max:255',
            'card_title_active' => 'boolean',
            'card_subtitle' => 'nullable|string|max:255',
            'card_subtitle_active' => 'boolean',
            'text_color' => 'required|string|max:7',
            'card_background_color' => 'required|string|max:7',
            'image' => 'nullable|image|max:2048',
            'delete_image' => 'nullable|boolean',
        ]);

        if ($request->delete_image && $vehicle->image) {
            Storage::delete($vehicle->image->path);
            $vehicle->image->delete();
        }

        if ($request->hasFile('image')) {
            if ($vehicle->image) {
                Storage::delete($vehicle->image->path);
                $vehicle->image->delete();
            }
            $path = $request->file('image')->store("landing", 'public');
            $vehicle->image()->create(['path' => $path]);
        }

        $vehicle->update($data);

        return redirect()->route('landing.vehicle.index')->with('success', 'Tarjeta actualizada.');
    }

    public function destroy(VehicleType $vehicle)
    {
        if ($vehicle->image) {
            Storage::delete($vehicle->image->path);
            $vehicle->image->delete();
        }

        $vehicle->delete();

        return redirect()->route('landing.vehicle.index')->with('success', 'Tarjeta eliminada.');
    }
}
