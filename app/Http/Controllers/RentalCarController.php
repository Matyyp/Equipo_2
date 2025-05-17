<?php

namespace App\Http\Controllers;

use App\Models\RentalCar;
use App\Models\Brand;
use App\Models\ModelCar;
use Illuminate\Http\Request;

class RentalCarController extends Controller
{
    public function index()
    {
        // Traemos todos los autos con marca y modelo para mostrarlos
        $rentalCars = RentalCar::with(['brand', 'model'])->get();
        return view('tenant.admin.rental_cars.index', compact('rentalCars'));
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
        $data = $request->validate([
            'brand_id'     => 'required|exists:brands,id_brand',
            'model_car_id' => 'required|exists:model_cars,id_model',
            'year'         => 'required|integer|min:1900|max:' . now()->year,
            'is_active'    => 'required|boolean',
            'images.*'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $rentalCar->update($data);

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
                         ->with('success', 'Auto de arriendo actualizado correctamente.');
    }

    public function destroy(RentalCar $rentalCar)
    {
        $rentalCar->delete();
        return redirect()->route('rental-cars.index')
                         ->with('success', 'Auto de arriendo eliminado correctamente.');
    }
}
