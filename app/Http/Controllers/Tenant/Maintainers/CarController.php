<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ModelCar;
use App\Models\Brand;
use App\Models\Car;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $car = Car::with('car_brand', 'car_model')->get();
        $car = $car->map(function ($car) {
            return [
                'id' => $car->id_car,
                'patent' => $car->patent,
                'brand' => $car->car_brand->name_brand,
                'model' => $car->car_model->name_model,
                'value_rent' => $car->value_rent,
            ];
        });

        $car = $car->toArray(); 

        return view('tenant.car.index', compact('car'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $models = ModelCar::all();

        return view('tenant.car.create', [
            'brands' => $brands,
            'models' => $models,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patent' => 'required|string|max:100',
            'value_rent' => 'nullable|string|max:255',
            'brand_id' => 'required|string|max:255',
            'model_id' => 'required|string|max:255',
        ]);
    
        Car::create([
            'patent' => $request->patent,
            'value_rent' => $request->value_rent,
            'id_brand' => $request->brand_id,
            'id_model' => $request->model_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $car = Car::where('id_car', $id)
        ->with('car_brand', 'car_model')
        ->first();
        $brands = Brand::all();
        $models = ModelCar::all();
        return view('tenant.car.edit', [
            'brands' => $brands,
            'models' => $models,
            'car'=> $car
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'patent' => 'required|string|max:100',
            'value_rent' => 'nullable|string|max:255',
            'brand_id' => 'required|string|max:255',
            'model_id' => 'required|string|max:255',
        ]);
    
        Car::where('id_car', $id)
        ->update([
            'patent' => $request->patent,
            'value_rent' => $request->value_rent,
            'id_brand' => $request->brand_id,
            'id_model' => $request->model_id,
        ]);

        return redirect()->route('autos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Car::where('id_car', $id)->delete();
        return redirect()->route('autos.index');
    }

    
}
