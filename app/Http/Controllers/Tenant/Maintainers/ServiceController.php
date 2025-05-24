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
        $sucursalId = $request->query('sucursal'); // o 'sucursal_id' según el nombre que uses
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
            'type_service' => 'required|in:parking_daily,parking_annual,car_wash,rent,extra',
            'id_branch_office' => 'required|exists:branch_offices,id_branch',
            'status' => 'required|in:available,unavailable',
        ]);

        // Solo validar existencia si el tipo no es 'extra'
        if ($validated['type_service'] !== 'extra') {
            $exists = Service::where('type_service', $validated['type_service'])
                ->where('id_branch_office', $validated['id_branch_office'])
                ->where('status', 'available')
                ->exists();

            if ($exists) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este tipo de servicio ya existe en esta sucursal.'
                    ], 409);
                }

                return redirect()
                    ->back()
                    ->withErrors(['type_service' => 'Este servicio ya está registrado en esta sucursal.']);
            }
        }

        // Crear servicio base
        $service = Service::create($validated);

        // Crear detalle según el tipo
        match ($validated['type_service']) {
            'rent'           => Rent::create(['id_service' => $service->id_service]),
            'car_wash'       => CarWash::create(['id_service' => $service->id_service]),
            'parking_daily',
            'parking_annual' => Parking::create(['id_service' => $service->id_service]),
             'extra'            => null,
        };

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Servicio creado correctamente.',
                'data' => $service
            ]);
        }

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
            'rent'           => 'Arriendo',
            'extra'          => 'Extra'
        ];

        // Obtener los últimos servicios por tipo (sin importar el status)
        $allServices = Service::where('id_branch_office', $id)
            ->orderByDesc('created_at')
            ->get();

        // Agrupar por tipo y tomar el último (puede ser available o disabled)
        $registrados = $allServices->groupBy('type_service')->map(fn($group) => $group->first());

        return view('tenant.admin.maintainer.service.show', [
            'tipos'       => $tipos,
            'registrados' => $registrados,
            'sucursal'    => $branch->name_branch_offices,
            'direccion'   => $branch->street,
            'sucursalId'  => $branch->id_branch,
        ]);
    }

    public function disable($id)
    {
        $service = Service::findOrFail($id);

        if ($service->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'El servicio ya está inactivo.'
            ]);
        }

        $service->status = 'not_available';
        $service->save();

        return response()->json([
            'success' => true,
            'message' => 'Servicio desactivado correctamente.'
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
            'type_service' => 'required|in:parking_daily,parking_annual,car_wash,rent,extra',
        ]);
    
        $old_service = Service::findOrFail($id);

        $old_service->update([
            'status' => 'not_available',
        ]);
    
        $new_service = Service::create([
            'name' => $request->name,
            'price_net' => $request->price_net,
            'type_service' => $request->type_service,
            'id_branch_office' => $old_service->id_branch_office,
        ]);
    
        Parking::create([
            'id_service' => $new_service->id_service
        ]);
    
        return redirect()->route('servicios.show', $old_service->id_branch_office);
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
