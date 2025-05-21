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
                ->addColumn('image', function($service) {
                    return $service->image ? '<img src="'.tenant_asset("".$service->image->path).'" width="80" class="img-thumbnail"/>' : '';
                })
                ->addColumn('title_status', function($service) {
                    return $service->title_active 
                        ? '<span class="badge bg-success">Activo</span>' 
                        : '<span class="badge bg-secondary">Inactivo</span>';
                })
                ->addColumn('secondary_text_status', function($service) {
                    return $service->secondary_text_active 
                        ? '<span class="badge bg-success">Activo</span>' 
                        : '<span class="badge bg-secondary">Inactivo</span>';
                })
                ->addColumn('small_text_status', function($service) {
                    return $service->small_text_active 
                        ? '<span class="badge bg-success">Activo</span>' 
                        : '<span class="badge bg-secondary">Inactivo</span>';
                })
                ->addColumn('title_color', function($service) {
                    return '<div style="width:30px; height:30px; background-color:'.$service->title_color.'; border:1px solid #ddd;"></div>';
                })
                ->addColumn('secondary_text_color', function($service) {
                    return '<div style="width:30px; height:30px; background-color:'.$service->secondary_text_color.'; border:1px solid #ddd;"></div>';
                })
                ->addColumn('small_text_color', function($service) {
                    return '<div style="width:30px; height:30px; background-color:'.$service->small_text_color.'; border:1px solid #ddd;"></div>';
                })
                ->addColumn('card_background_color', function($service) {
                    return '<div style="width:30px; height:30px; background-color:'.$service->card_background_color.'; border:1px solid #ddd;"></div>';
                })
                ->addColumn('actions', function($service) {
                    $editUrl = route('landing.service.edit', $service->service_card_id);
                    $deleteUrl = route('landing.service.destroy', $service->service_card_id);
                    
                    return '
                        <div class="d-flex justify-content-center">
                            <a href="'.$editUrl.'" class="btn btn-sm btn-outline-primary me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="'.$deleteUrl.'" method="POST" class="d-inline">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'¿Estás seguro de eliminar este servicio?\')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns([
                    'image', 
                    'title_status',
                    'secondary_text_status',
                    'small_text_status',
                    'title_color',
                    'secondary_text_color',
                    'small_text_color',
                    'card_background_color',
                    'actions'
                ])
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