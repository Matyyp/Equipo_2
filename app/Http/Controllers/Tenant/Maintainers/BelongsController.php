<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Belong;
use App\Models\Car;

class BelongsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_owner'   => 'required|exists:owners,id_owner',
            'vehiculos'  => 'required|array',
            'vehiculos.*'=> 'exists:cars,id_car',
        ]);

        foreach ($request->vehiculos as $id_car) {
            Belong::create([
                'id_owner' => $request->id_owner,
                'id_car'   => $id_car,
            ]);
        }

        return redirect()->back();
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $datos = Belong::where('id_owner', $id)
        ->with('belongs_owner', 'belongs_car', 'belongs_car',
        'belongs_car.car_brand' , 'belongs_car.car_model')
        ->get();

        $datos = $datos->map(function ($owner) use ($id) {
            return [
                'id'=>$owner->id,
                'id_owner' => $id,
                'name' => optional($owner->belongs_owner)->name,
                'id_car' => optional($owner->belongs_car)->id_car,
                'brand' => optional(optional($owner->belongs_car)->car_brand)->name_brand,
                'model' => optional(optional($owner->belongs_car)->car_model)->name_model,
            ];
        })->toArray();
        
        return view('tenant.admin.maintainer.belongs.index', ['datos' => $datos, 'id'=>$id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $datos = Car::whereDoesntHave('car_belongs', function ($query) use ($id) {
            $query->where('id_owner', $id);
        })
        ->with('car_brand', 'car_model')
        ->get();
        $datos = $datos->map(function ($car) use ($id) {
            return [
                'id_owner' => $id,
                'id_car'   => $car->id_car,
                'placa'  => $car->patent,
                'brand'    => optional($car->car_brand)->name_brand,
                'model'    => optional($car->car_model)->name_model,
            ];
        })->toArray();

        return view('tenant.admin.maintainer.belongs.edit', [
            'datos' => $datos,
            'id'    => $id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        belong::where('id', $id)->delete();
        return redirect()->back();
    }
}
