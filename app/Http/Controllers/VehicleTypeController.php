<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use App\Models\VehicleTypeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class VehicleTypeController extends Controller
{
    public function index()
    {
        return view('tenant.admin.landing.vehicle.index');
    }

    public function data(Request $request)
    {
        return DataTables::of(VehicleType::with('image'))
            ->addColumn('image', fn($v) => $v->image ? '<img src="' . tenant_asset("" . $v->image->path) . '" width="100"/>' : '')
            ->addColumn('card_title_active', fn($v) => $v->card_title_active ? 'Yes' : 'No')
            ->addColumn('card_subtitle_active', fn($v) => $v->card_subtitle_active ? 'Yes' : 'No')
            ->addColumn('card_background_color', fn($v) => '<div style="width: 30px; height: 30px; background-color: '.$v->card_background_color.';"></div>')
            ->addColumn('text_color', fn($v) => '<div style="width: 30px; height: 30px; background-color: '.$v->text_color.';"></div>')

            ->addColumn('acciones', function ($v) {
                $edit = route('landing.vehicle.edit',$v); // ajustar a edit si lo deseas
                $delete = route('landing.vehicle.destroy', $v);
                $csrf = csrf_field();
                $method = method_field('DELETE');
                return <<<HTML
                    <a href="{$edit}" class="btn btn-outline-secondary btn-sm text-dark me-1"><i class="fas fa-pen"></i></a>
                    <form action="{$delete}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar tarjeta?')">
                        {$csrf}{$method}
                        <button class="btn btn-outline-secondary btn-sm text-dark"><i class="fas fa-trash"></i></button>
                    </form>
                HTML;
            })
            ->rawColumns(['image', 'card_background_color', 'text_color', 'acciones'])
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
