<?php

namespace App\Http\Controllers;

use App\Models\RentalCar;
use App\Models\Brand;
use App\Models\ModelCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class RentalCarController extends Controller
{
    public function index()
    {
        // Traemos todos los autos con marca y modelo para mostrarlos
        $rentalCars = RentalCar::with(['brand', 'model'])->get();
        return view('tenant.admin.rental_cars.index', compact('rentalCars'));
    }

    public function data(Request $request)
    {
        return DataTables::of(RentalCar::with(['brand','model']))
            ->addColumn('marca', fn($c) => $c->brand->name_brand)
            ->addColumn('modelo', fn($c) => $c->model->name_model)
            ->addColumn('estado', function($c) {
                return $c->is_active
                ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                : '<span class="border border-secondary text-secondary px-2 py-1 rounded">Inactivo</span>';
            })
            ->addColumn('acciones', function($c) {
                $v = route('rental-cars.show',   $c);
                $e = route('rental-cars.edit',   $c);
                $d = route('rental-cars.destroy',$c);
                $token  = csrf_field();
                $method = method_field('DELETE');

                return <<<HTML
                <a href="{$v}" class="btn btn-outline-secondary btn-sm text-dark me-1" title="Ver">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{$e}" class="btn btn-outline-secondary btn-sm text-dark me-1" title="Editar">
                    <i class="fas fa-pen"></i>
                </a>
                <form action="{$d}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar?')">
                    {$token}{$method}
                    <button class="btn btn-outline-secondary btn-sm text-dark" title="Eliminar">
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
        // Para los selects de marca y modelo
        $brands = Brand::pluck('name_brand', 'id_brand');
        $models = ModelCar::pluck('name_model', 'id_model');
        return view('tenant.admin.rental_cars.create', compact('brands', 'models'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand_id'     => 'required|exists:brands,id_brand',
            'model_car_id' => 'required|exists:model_cars,id_model',
            'year'         => 'required|integer|min:1900|max:' . now()->year,
            'is_active'    => 'required|boolean',
            'images.*'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $rentalCar = RentalCar::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // esto usa el disco "public" tenantizado
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
        $rentalCar->load(['brand', 'model', 'images']);
        return view('tenant.admin.rental_cars.show', compact('rentalCar'));
    }

    public function edit(RentalCar $rentalCar)
    {
        $brands = Brand::pluck('name_brand', 'id_brand');
        $models = ModelCar::pluck('name_model', 'id_model');
        return view('tenant.admin.rental_cars.edit', compact('rentalCar', 'brands', 'models'));
    }

    public function update(Request $request, RentalCar $rentalCar)
    {
        // 1) Validación (incluyendo delete_images)
        $data = $request->validate([
            'brand_id'        => 'required|exists:brands,id_brand',
            'model_car_id'    => 'required|exists:model_cars,id_model',
            'year'            => 'required|integer|min:1900|max:' . now()->year,
            'is_active'       => 'required|boolean',
            'images.*'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'delete_images'   => 'nullable|array',
            'delete_images.*' => 'integer|exists:rental_car_images,id',
        ]);

        // 2) Eliminar físicamente y en BD las imágenes marcadas
        if (!empty($data['delete_images'])) {
            foreach ($data['delete_images'] as $imgId) {
                $img = $rentalCar->images()->find($imgId);
                if ($img) {
                    Storage::disk('public')->delete($img->path);
                    $img->delete();
                }
            }
        }

        // 3) Actualizar el RentalCar
        $rentalCar->update([
            'brand_id'     => $data['brand_id'],
            'model_car_id' => $data['model_car_id'],
            'year'         => $data['year'],
            'is_active'    => $data['is_active'],
        ]);

        // 4) Guardar nuevas imágenes
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
