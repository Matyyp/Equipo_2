<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Belong;
use App\Models\Owner;
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
        $owner = Owner::where('id_owner', $id)
            ->with('owner_belongs', 'owner_belongs.belongs_car',
                'owner_belongs.belongs_car.car_brand',
                'owner_belongs.belongs_car.car_model')
            ->first();

        $name = $owner->name ?? 'Sin nombre';

        $datos = collect($owner->owner_belongs)->map(function ($belong) {
            return [
                'id' => $belong->id,
                'name' => optional($belong->belongs_owner)->name, // redundante pero mantenido por consistencia
                'id_car' => optional($belong->belongs_car)->id_car,
                'patent' => optional($belong->belongs_car)->patent,
                'brand' => optional(optional($belong->belongs_car)->car_brand)->name_brand,
                'model' => optional(optional($belong->belongs_car)->car_model)->name_model,
            ];
        })->toArray();

        return view('tenant.admin.maintainer.belongs.index', [
            'datos' => $datos,
            'id' => $id,
            'name' => $name,
        ]);
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
        $name = Owner::where('id_owner', $id)->value('name');
        $datos = $datos->map(function ($car) use ($id) {
            return [
                'id_owner' => $id,
                'id_car'   => $car->id_car,
                'patent'  => $car->patent,
                'brand'    => optional($car->car_brand)->name_brand,
                'model'    => optional($car->car_model)->name_model,
            ];
        })->toArray();

        return view('tenant.admin.maintainer.belongs.edit', [
            'datos' => $datos,
            'id'    => $id,
            'name'  => $name
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
