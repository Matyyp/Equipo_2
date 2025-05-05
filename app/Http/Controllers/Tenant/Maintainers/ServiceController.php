<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Parking;
use App\Models\Rent;
use App\Models\CarWash;
use App\Models\BranchOffice;

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
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price_net' => 'required|numeric|min:0',
            'type_service' => 'required|in:parking_daily,parking_annual,car_wash,rent',
            'id_branch_office' => 'required|exists:branch_offices,id_branch',
        ]);
    
        // Verificar si ya existe ese tipo de servicio en la sucursal
        $exists = Service::where('type_service', $validated['type_service'])
                        ->where('id_branch_office', $validated['id_branch_office'])
                        ->exists();
    
        if ($exists) {
            // Si es JSON (fetch desde JavaScript), responder con error
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este tipo de servicio ya existe en esta sucursal.'
                ], 409);
            }
    
            // Si es una petición normal, redirigir con error
            return redirect()
                ->back()
                ->withErrors(['type_service' => 'Este servicio ya está registrado en esta sucursal.']);
        }
    
        // Crear servicio
        $service = Service::create($validated);
    
        // Crear entidad relacionada según el tipo
        match ($validated['type_service']) {
            'rent'           => Rent::create(['id_service' => $service->id_service]),
            'car_wash'       => CarWash::create(['id_service' => $service->id_service]),
            'parking_daily',
            'parking_annual' => Parking::create(['id_service' => $service->id_service]),
        };
    
        // Si es JSON (fetch), responder como API
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Servicio activado correctamente.',
                'data' => $service
            ]);
        }
    
        // Si viene de un formulario HTML tradicional
        return redirect()->route('servicios.show', $validated['id_branch_office'])
                         ->with('success', 'Servicio creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $branch = BranchOffice::findOrFail($id);

        $tipos = [
            'parking_daily'  => 'Estacionamiento Diario',
            'parking_annual' => 'Estacionamiento Anual',
            'car_wash'       => 'Lavado de Autos',
            'rent'           => 'Arriendo',
        ];
    
        $registrados = Service::where('id_branch_office', $id)->get()->keyBy('type_service');
    
        return view('tenant.admin.maintainer.service.show', [
            'tipos'      => $tipos,
            'registrados'=> $registrados,
            'sucursal'   => $branch->name_branch_offices,
            'direccion'  => $branch->street,
            'sucursalId' => $branch->id_branch,
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
