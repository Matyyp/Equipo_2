<?php

namespace App\Http\Controllers;

use App\Models\RentalCar;
use App\Models\Brand;
use App\Models\ModelCar;
use App\Models\BranchOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class RentalCarController extends Controller
{
    public function index()
    {
        // Traemos todos los autos con marca y modelo para mostrarlos
        $rentalCars = RentalCar::with(['brand','model','branchOffice'])->get();
        return view('tenant.admin.rental_cars.index', compact('rentalCars'));
    }

    public function data(Request $request)
    {
        return DataTables::of(RentalCar::with(['brand','model','branchOffice','accidents']))
            ->addColumn('marca', fn($c) => $c->brand->name_brand)
            ->addColumn('modelo', fn($c) => $c->model->name_model)
            ->addColumn('year',   fn($c) => $c->year)
            ->addColumn('estado', function($c) {
                // Usar el accesor visual_status del modelo
                return $c->visual_status === 'activo'
                    ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                    : '<span class="border border-secondary text-secondary px-2 py-1 rounded">Inactivo</span>';
            })
            ->addColumn('sucursal', fn($c) => optional($c->branchOffice)->name_branch_offices ?: '—')
            ->addColumn('price', fn($c) => '$'.number_format($c->price_per_day, 0, ',', '.'))
            ->addColumn('acciones', function($c) {
                $v = route('rental-cars.show',   $c);
                $e = route('rental-cars.edit',   $c);
                $d = route('rental-cars.destroy',$c);
                $accidents = route('accidente.index', ['rental_car_id' => $c->id]);
                $token  = csrf_field();
                $method = method_field('DELETE');
                return <<<HTML
                <a href="{$v}" class="btn btn-outline-info btn-sm text-info" title="Ver">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{$e}" class="btn btn-outline-info btn-sm text-info" title="Editar">
                    <i class="fas fa-pen"></i>
                </a>
                <a href="{$accidents}" class="btn btn-outline-info btn-sm text-info" title="Accidentes">
                    <i class="fas fa-car-crash"></i>
                </a>
                <form action="{$d}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar?')">
                    {$token}{$method}
                    <button class="btn btn-outline-info btn-sm text-info" title="Eliminar">
                    <i class="fas fa-trash"></i>
                    </button>
                </form>
                HTML;
            })
            ->rawColumns(['estado','acciones'])
            ->make(true);
    }

    public function create()
    {
        $brands = Brand::pluck('name_brand', 'id_brand');
        $models = ModelCar::pluck('name_model', 'id_model');
        $branches = BranchOffice::pluck('name_branch_offices', 'id_branch');
        return view('tenant.admin.rental_cars.create', compact('brands', 'models', 'branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand_id'     => 'required|exists:brands,id_brand',
            'model_car_id' => 'required|exists:model_cars,id_model',
            'year'         => 'required|integer|min:1900|max:' . now()->year,
            'is_active'    => 'required|boolean',
            'branch_office_id' => 'required|exists:branch_offices,id_branch',
            'passenger_capacity' => 'required|integer|min:1',
            'transmission'       => 'required|in:manual,automatic',
            'luggage_capacity'   => 'required|integer|min:0',
            'price_per_day'      => 'required|numeric|min:0',
            'images.*'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $rentalCar = RentalCar::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('rental_cars', 'public');
                $rentalCar->images()->create([
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('rental-cars.index')
                         ->with('success', 'Auto de arriendo creado correctamente.');
    }

    public function show(RentalCar $rentalCar)
    {
        $rentalCar->load(['brand', 'model', 'images', 'branchOffice', 'accidents']);
        return view('tenant.admin.rental_cars.show', compact('rentalCar'));
    }

    public function edit(RentalCar $rentalCar)
    {
        // Asegúrate de cargar los accidentes
        $rentalCar->load(['accidents']);

        // Si tiene accidente en progreso, forzar is_active a 0 SOLO para el formulario
        if ($rentalCar->accidents->contains(fn($a) => $a->status === 'in progress')) {
            $rentalCar->is_active = 0;
        }

        $brands   = Brand::pluck('name_brand', 'id_brand');
        $models   = ModelCar::pluck('name_model', 'id_model');
        $branches = BranchOffice::pluck('name_branch_offices', 'id_branch');

        return view(
            'tenant.admin.rental_cars.edit',
            compact('rentalCar','brands','models','branches')
        );
    }

    public function update(Request $request, RentalCar $rentalCar)
    {
        $data = $request->validate([
            'brand_id'           => 'required|exists:brands,id_brand',
            'model_car_id'       => 'required|exists:model_cars,id_model',
            'year'               => 'required|integer|min:1900|max:'.now()->year,
            'branch_office_id'   => 'required|exists:branch_offices,id_branch',
            'is_active'          => 'required|boolean',
            'passenger_capacity' => 'required|integer|min:1',
            'transmission'       => 'required|in:manual,automatic',
            'luggage_capacity'   => 'required|integer|min:0',
            'price_per_day'      => 'required|numeric|min:0',
            'images.*'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'delete_images'      => 'nullable|array',
            'delete_images.*'    => 'integer|exists:rental_car_images,id',
        ]);

        // 1. Eliminar imágenes marcadas
        if (!empty($data['delete_images'])) {
            foreach ($data['delete_images'] as $imgId) {
                $img = $rentalCar->images()->find($imgId);
                if ($img) {
                    Storage::disk('public')->delete($img->path);
                    $img->delete();
                }
            }
        }

        // 2. Actualizar el RentalCar con todos los campos
        $rentalCar->update([
            'brand_id'           => $data['brand_id'],
            'model_car_id'       => $data['model_car_id'],
            'year'               => $data['year'],
            'branch_office_id'   => $data['branch_office_id'],
            'is_active'          => $data['is_active'],
            'passenger_capacity' => $data['passenger_capacity'],
            'transmission'       => $data['transmission'],
            'luggage_capacity'   => $data['luggage_capacity'],
            'price_per_day'      => $data['price_per_day'],
        ]);

        // 3. Guardar nuevas imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('rental_cars', 'public');
                $rentalCar->images()->create(['path' => $path]);
            }
        }

        return redirect()
            ->route('rental-cars.index')
            ->with('success', 'Auto de arriendo actualizado correctamente.');
    }

    public function destroy(RentalCar $rentalCar)
    {
        $rentalCar->delete();
        return redirect()->route('rental-cars.index')
                         ->with('success', 'Auto de arriendo eliminado correctamente.');
    }
}