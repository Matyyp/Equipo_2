<?php

namespace App\Http\Controllers;

use App\Models\Accident;
use App\Models\AccidentPhoto;
use App\Models\RentalCar;
use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\RegisterRent;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AccidentController extends Controller
{
public function index(Request $request)
{
    $rentalCarId = $request->get('rental_car_id');
    $rentalCar = null;
    $accidents = collect(); // <- Asegura que exista aunque no haya datos

    if ($rentalCarId) {
        // Cargar también la relación 'accidents' con las fotos para la vista
        $rentalCar = RentalCar::with(['brand', 'model', 'accidents.photos'])->find($rentalCarId);
        $accidents = $rentalCar ? $rentalCar->accidents : collect();
    } else {
        // Si no hay auto seleccionado, mostrar todos los accidentes con fotos
        $accidents = Accident::with(['photos', 'rentalCar.brand', 'rentalCar.model'])->get();
    }

    return view('tenant.admin.accident.index', compact('rentalCar', 'accidents'));
}

    public function data(Request $request)
    {
        
        $rentalCarId = $request->get('rental_car_id');

        $query = Accident::with(['photos', 'rent']);
        if ($rentalCarId) {
            $query->where('rental_car_id', $rentalCarId);
        }   

        return DataTables::of($query)
            ->addColumn('id_rent', function($a) {
                return $a->id_rent ?? '<span class="text-muted">-</span>';
            })
            ->addColumn('name_accident', fn($a) => $a->name_accident)
            ->addColumn('description', fn($a) => $a->description)
            ->addColumn('bill_number', fn($a) => $a->bill_number)
            ->addColumn('description_accident_term', fn($a) => $a->description_accident_term)
            // Fotos
            ->addColumn('photo', function ($a) {
                $photos = $a->photos;
                if ($photos->count() > 0) {
                    $modalId = 'modal-photo-' . $a->id;
                    $carouselId = 'carousel-' . $a->id;
                    $carouselItems = '';
                    foreach ($photos as $i => $photo) {
                        $url = tenant_asset($photo->photo);
                        $active = $i === 0 ? 'active' : '';
                        $carouselItems .= <<<HTML
                            <div class="carousel-item {$active}">
                                <img src="{$url}" alt="Foto accidente" style="max-width:100%;height:auto;border-radius:4px;border:1px solid #ddd;">
                            </div>
                        HTML;
                    }
                    $indicators = '';
                    if ($photos->count() > 1) {
                        $indicators = '<ol class="carousel-indicators">';
                        foreach ($photos as $i => $photo) {
                            $active = $i === 0 ? 'active' : '';
                            $indicators .= "<li data-target=\"#{$carouselId}\" data-slide-to=\"{$i}\" class=\"{$active}\"></li>";
                        }
                        $indicators .= '</ol>';
                    }
                    return <<<HTML
                        <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#{$modalId}">
                            <i class="fas fa-image"></i> Ver Fotos
                        </button>
                        <div class="modal fade" id="{$modalId}" tabindex="-1" role="dialog" aria-labelledby="{$modalId}Label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="{$modalId}Label">Fotos del Accidente</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <div id="{$carouselId}" class="carousel slide" data-ride="carousel">
                                            {$indicators}
                                            <div class="carousel-inner">
                                                {$carouselItems}
                                            </div>
                                            <a class="carousel-control-prev" href="#{$carouselId}" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Anterior</span>
                                            </a>
                                            <a class="carousel-control-next" href="#{$carouselId}" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Siguiente</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    HTML;
                }
                return '<span class="text-muted">Sin foto</span>';
            })
            // Estado del accidente
->addColumn('status', fn($a) => $a->status === 'in progress'
    ? '<span class="border border-secondary text-secondary px-2 py-1 rounded d-inline-flex align-items-center" style="height: 31px;">En progreso</span>'
    : '<span class="border border-success text-success px-2 py-1 rounded d-inline-flex align-items-center" style="height: 31px;">Completado</span>'
)
            ->addColumn('created_at', fn($a) => $a->created_at ? $a->created_at->format('Y-m-d H:i') : '')
            ->addColumn('acciones', function ($a) {
                $editUrl = route('accidente.edit', $a->id);
                $deleteUrl = route('accidente.destroy', $a->id);
                $pdfUrl = route('accidente.downloadPdf', $a->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');
                $btnEstado = '';
                if ($a->status === 'in progress') {
                    $btnEstado = <<<HTML
                        <button class="btn btn-sm btn-outline-info btn-mark-complete" data-id="{$a->id}" title="Marcar como completado">
                            <i class="fas fa-check"></i>
                        </button>
                    HTML;
                }
                $btnPdf = <<<HTML
                    <a href="{$pdfUrl}" class="btn btn-sm btn-outline-info" title="Descargar PDF" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                HTML;

                $btnDelete = <<<HTML
                    <form action="{$deleteUrl}" method="POST" style="display:inline-block;" class="form-delete-accident" data-id="{$a->id}">
                        {$csrf}
                        {$method}
                        <button type="button" class="btn btn-sm btn-outline-info btn-delete-accident" data-id="{$a->id}" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                HTML;

                return <<<HTML
                    <a href="{$editUrl}" class="btn btn-sm btn-outline-info" title="Editar"><i class="fas fa-pen"></i></a>
                    {$btnDelete}
                    {$btnEstado}
                    {$btnPdf}
                HTML;
            })
            ->rawColumns(['id_rent', 'photo', 'acciones', 'status'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $idRent = $request->get('id_rent');
        $rentalCarId = $request->get('rental_car_id');
        $selectedRent = null;
        $rentalCar = null;
        $registerRents = collect();

        if ($idRent) {
            // Solo un arriendo
            $selectedRent = RegisterRent::with(['rentalCar.brand', 'rentalCar.model', 'rentalCar.accidents'])->find($idRent);
            $rentalCar = $selectedRent?->rentalCar;
        } elseif ($rentalCarId) {
            // Listar solo arriendos de ese auto, con accidentes cargados
            $rentalCar = \App\Models\RentalCar::with(['brand', 'model', 'accidents'])->find($rentalCarId);
            $registerRents = RegisterRent::where('rental_car_id', $rentalCarId)
                ->with(['rentalCar.brand', 'rentalCar.model', 'rentalCar.accidents'])
                ->get();
        } else {
            // Todos los arriendos
            $registerRents = RegisterRent::with(['rentalCar.brand', 'rentalCar.model', 'rentalCar.accidents'])->get();
        }

        return view('tenant.admin.accident.create', compact('rentalCar', 'selectedRent', 'registerRents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_rent'                   => 'required|exists:register_rents,id',
            'name_accident'             => 'required|string|max:255',
            'description'               => 'nullable|string',
            'bill_number'               => 'nullable|string|max:255',
            'description_accident_term' => 'nullable|string',
            'rental_car_id'             => 'required|exists:rental_cars,id',
            'photos.*'                  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data['status'] = 'in progress';

        $accident = Accident::create($data);

        // Desactivar el auto de arriendo
        $rentalCar = \App\Models\RentalCar::find($data['rental_car_id']);
        if ($rentalCar) {
            $rentalCar->is_active = 0;
            $rentalCar->save();
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('accident', 'public');
                $accident->photos()->create(['photo' => $path]);
            }
        }

        return redirect()->route('accidente.index', ['rental_car_id' => $data['rental_car_id']])
            ->with('success', 'Accidente registrado correctamente.');
    }

    public function edit(Accident $accidente)
    {
        // Cargar rentalCar con accidentes para el accesor visual_status
        $rentalCar = $accidente->rentalCar()->with(['brand', 'model', 'accidents'])->first();
        $photos = $accidente->photos;
        return view('tenant.admin.accident.edit', compact('accidente', 'rentalCar', 'photos'));
    }

    public function update(Request $request, Accident $accidente)
    {
        $data = $request->validate([
            'name_accident'             => 'required|string|max:255',
            'description'               => 'nullable|string',
            'bill_number'               => 'nullable|string|max:255',
            'description_accident_term' => 'nullable|string',
            'photos.*'                  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $accidente->update($data);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('accident', 'public');
                $accidente->photos()->create(['photo' => $path]);
            }
        }

        return redirect()->route('accidente.index', ['rental_car_id' => $accidente->rental_car_id])
            ->with('success', 'Accidente actualizado correctamente.');
    }

    public function destroy(Accident $accidente)
    {
        $rentalCarId = $accidente->rental_car_id;
        $accidente->delete();

        return redirect()->route('accidente.index', ['rental_car_id' => $rentalCarId])
            ->with('success', 'Accidente eliminado correctamente.');
    }

    public function destroyPhoto($accidenteId, $photoId)
    {
        $photo = \App\Models\AccidentPhoto::where('accident_id', $accidenteId)
            ->where('id', $photoId)
            ->firstOrFail();

        if ($photo->photo && Storage::disk('public')->exists($photo->photo)) {
            Storage::disk('public')->delete($photo->photo);
        }

        $photo->delete();

        return back()->with('success', 'Foto eliminada correctamente.');
    }

public function markComplete(Accident $accidente)
    {
        $accidente->update(['status' => 'complete']);

        // Reactivar auto si no tiene otros accidentes en progreso
        $rentalCar = $accidente->rentalCar;
        if ($rentalCar && !$rentalCar->accidents()->where('status', 'in progress')->exists()) {
            $rentalCar->is_active = 1;
            $rentalCar->save();
        }

        return response()->json(['success' => true]);
    }

    public function downloadPdf(Accident $accidente)
    {
        // Cargar rentalCar con accidentes para el accesor visual_status si se usa en la vista PDF
        $rentalCar = $accidente->rentalCar()->with(['brand', 'model', 'accidents'])->first();

        $logo = Business::value('logo');
        $logoPath = $logo ? storage_path('app/public/' . $logo) : null;
        $logoBase64 = null;
        if ($logoPath && file_exists($logoPath)) {
            $logoBase64 = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Fotos del accidente
        $photosBase64 = [];
        foreach ($accidente->photos as $photo) {
            $photoPath = storage_path('app/public/' . $photo->photo);
            if (file_exists($photoPath)) {
                $photosBase64[] = 'data:' . mime_content_type($photoPath) . ';base64,' . base64_encode(file_get_contents($photoPath));
            }
        }

        $pdf = Pdf::loadView('tenant.admin.accident.pdf', compact('accidente', 'rentalCar', 'logoBase64', 'photosBase64'));
        $filename = 'Accidente_' . $accidente->id . '.pdf';
        return $pdf->download($filename);
    }
}