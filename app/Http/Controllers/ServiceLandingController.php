<?php

namespace App\Http\Controllers;

use App\Models\ServiceLanding;
use App\Models\ServiceLandingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ServiceLandingController extends Controller
{
    public function index()
    {
        return view('tenant.admin.landing.service.index');
    }

    public function data()
    {
        $services = ServiceLanding::with('image')->select('service_landings.*');

        return DataTables::of($services)
            ->addColumn('image', function ($s) {
                return $s->image 
                    ? '<img src="' . tenant_asset($s->image->path) . '" width="80" class="img-thumbnail"/>' 
                    : '';
            })

            ->addColumn('title', function ($s) {
                $badge = $s->title_active 
                    ? '<span class="badge bg-success mb-1">Activo</span>' 
                    : '<span class="badge bg-secondary mb-1">Inactivo</span>';

                return $badge . '<div>' . e($s->title) . '</div>';
            })

            ->addColumn('secondary_text', function ($s) {
                $badge = $s->secondary_text_active 
                    ? '<span class="badge bg-success mb-1">Activo</span>' 
                    : '<span class="badge bg-secondary mb-1">Inactivo</span>';

                return $badge . '<div>' . e($s->secondary_text) . '</div>';
            })

            ->addColumn('small_text', function ($s) {
                $badge = $s->small_text_active 
                    ? '<span class="badge bg-success mb-1">Activo</span>' 
                    : '<span class="badge bg-secondary mb-1">Inactivo</span>';

                return $badge . '<div>' . e($s->small_text) . '</div>';
            })

            ->addColumn('colores', function($s) {
                return '
                    <div class="d-flex flex-column gap-1 small">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:15px;height:15px;background-color:' . $s->title_color . ';border-radius:50%;border:1px solid #ccc;"></span> Título
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:15px;height:15px;background-color:' . $s->secondary_text_color . ';border-radius:50%;border:1px solid #ccc;"></span> Texto Secundario
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:15px;height:15px;background-color:' . $s->small_text_color . ';border-radius:50%;border:1px solid #ccc;"></span> Texto Pequeño
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:15px;height:15px;background-color:' . $s->card_background_color . ';border-radius:50%;border:1px solid #ccc;"></span> Fondo Tarjeta
                        </div>
                    </div>
                ';
            })

            ->addColumn('actions', function ($s) {
                $editUrl = route('landing.service.edit', $s->service_card_id);
                $deleteUrl = route('landing.service.destroy', $s->service_card_id);

                return '
                    <div class="d-flex justify-content-center">
                        <a href="' . $editUrl . '" class="btn btn-outline-secondary btn-sm text-dark me-1"><i class="fas fa-pen"></i></a>
                        </a>
                        <form action="' . $deleteUrl . '" method="POST" class="d-inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-outline-secondary btn-sm text-dark" onclick="return confirm(\'¿Estás seguro de eliminar este servicio?\')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                ';
            })

            ->rawColumns(['image', 'title', 'secondary_text', 'small_text', 'colores', 'actions'])
            ->make(true);
    }



    public function create()
    {
        return view('tenant.admin.landing.service.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'title_active' => 'boolean',
            'secondary_text' => 'nullable|string|max:255',
            'secondary_text_active' => 'boolean',
            'small_text' => 'nullable|string|max:255',
            'small_text_active' => 'boolean',
            'title_color' => 'required|string|max:7',
            'secondary_text_color' => 'required|string|max:7',
            'small_text_color' => 'required|string|max:7',
            'card_background_color' => 'required|string|max:7',
            'image' => 'nullable|image|max:2048',
        ]);

        $service = ServiceLanding::create($data);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store("landing", 'public');
            $service->image()->create(['path' => $path]);
        }

        return redirect()->route('landing.service.index')->with('success', 'Service card created.');
    }

    public function edit(ServiceLanding $serviceLanding)
    {
        return view('tenant.admin.landing.service.edit', compact('serviceLanding'));
    }

    public function update(Request $request, ServiceLanding $serviceLanding)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'title_active' => 'boolean',
            'secondary_text' => 'nullable|string|max:255',
            'secondary_text_active' => 'boolean',
            'small_text' => 'nullable|string|max:255',
            'small_text_active' => 'boolean',
            'title_color' => 'required|string|max:7',
            'secondary_text_color' => 'required|string|max:7',
            'small_text_color' => 'required|string|max:7',
            'card_background_color' => 'required|string|max:7',
            'image' => 'nullable|image|max:2048',
            'delete_image' => 'nullable|boolean',
        ]);

        if ($request->delete_image && $serviceLanding->image) {
            Storage::disk('public')->delete($serviceLanding->image->path);
            $serviceLanding->image->delete();
        }

        if ($request->hasFile('image')) {
            if ($serviceLanding->image) {
                Storage::disk('public')->delete($serviceLanding->image->path);
                $serviceLanding->image->delete();
            }

            $path = $request->file('image')->store("landing", 'public');
            $serviceLanding->image()->create(['path' => $path]);
        }

        $serviceLanding->update($data);

        return redirect()->route('landing.service.index')->with('success', 'Tarjeta Servicio Actualizada.');
    }

    public function destroy(ServiceLanding $serviceLanding)
    {
        if ($serviceLanding->image) {
            Storage::disk('public')->delete($serviceLanding->image->path);
            $serviceLanding->image->delete();
        }

        $serviceLanding->delete();

        return redirect()->route('landing.service.index')->with('success', 'Tarjeta Servicio Eliminada.');
    }
}