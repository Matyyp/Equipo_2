<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Parking;
use App\Models\Rent;
use App\Models\CarWash;


class ServiceController extends Controller
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
    public function create(Request $request)
    {
        $sucursalId = $request->query('sucursal_id');
        return view('tenant.admin.maintainer.service.create', compact('sucursalId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price_net' => 'required|numeric|min:0',
            'type_service' => 'required|in:parking_daily,parking_annual,car_wash,rent',
            'id_branch_office' => 'required|exists:branch_offices,id_branch',
        ]);
    
        $service = Service::create([
            'name' => $request->name,
            'price_net' => $request->price_net,
            'type_service' => $request->type_service,
            'id_branch_office' => $request->id_branch_office,
        ]);

        if($request->type_service=='rent'){

            Rent::create([
                'id_service' => $service->id_service
            ]);

        }elseif($request->type_service=='car_wash'){

            CarWash::create([
                'id_service' => $service->id_service
            ]);

        }elseif($request->type_service=='parking_daily' || $request->type_service=='parking_annual'  ){

            Parking::create([
                'id_service' => $service->id_service
            ]);

        }

        return redirect()->route('servicios.show', $request->id_branch_office); 


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Service::where('id_branch_office', $id)
        ->with('service_branch_office')
        ->get();

        return view('tenant.admin.maintainer.service.show', [
            'data' => $data,
            'direccion' => optional($data->first()?->service_branch_office)->street,
            'sucursal' => optional($data->first()?->service_branch_office)->name_branch_offices,
            'sucursalId' => $id
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $service = Service::findOrFail($id);
        return view('tenant.admin.maintainer.service.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price_net' => 'required|numeric|min:0',
            'type_service' => 'required|in:parking_daily,parking_annual,car_wash,rent',
        ]);
    
        Service::where('id_service', $id)->update([
            'name' => $request->name,
            'price_net' => $request->price_net,
            'type_service' => $request->type_service,
        ]);

        return redirect()->route('servicios.show', $id); 


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Service::where('id_service', $id)->delete();
        return redirect()->back();
    }
}
