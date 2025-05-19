<?php

namespace App\Http\Controllers\Tenant\CarWash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\CarWash;
use App\Models\ParkingRegister;
class CarWashController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $branchOfficeId = $user->id_branch_office;

        $lavados = [
            'Lavado Auto Pequeño',
            'Lavado Auto Mediano',
            'Lavado Auto Grande',
        ];

        $registrados = Service::where('id_branch_office', $branchOfficeId)
            ->where('type_service', 'car_wash')
            ->where('status', 'available')
            ->get()
            ->keyBy('name');

        return view('tenant.admin.carwash.create', compact('lavados', 'registrados', 'branchOfficeId'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $branchOfficeId = $request->input('id_branch_office', $user->id_branch_office);

        // Lavados activos tipo car_wash
        $washServices = Service::where('id_branch_office', $branchOfficeId)
            ->where('type_service', 'car_wash')
            ->where('status', 'available')
            ->get();

        return view('tenant.admin.carwash.create', compact('washServices', 'branchOfficeId'));
    }
    // CarWashController.php
    public function getByBranch()
{
    $branchOfficeId = auth()->user()->id_branch_office;

    $lavados = Service::where('id_branch_office', $branchOfficeId)
        ->where('type_service', 'car_wash')
        ->where('status', 'available')
        ->get(['id_service', 'name']);

    return response()->json($lavados);
}



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price_net'      => 'required|numeric|min:0',
            'id_branch_office' => 'required|exists:branch_offices,id_branch',

        ]);

        // Verifica si ya existe un servicio de lavado con ese nombre en la misma sucursal
        $alreadyActive = Service::where('id_branch_office', $validated['id_branch_office'])
            ->where('type_service', 'car_wash')
            ->where('name', $validated['name'])
            ->where('status', 'available')
            ->exists();

        if ($alreadyActive) {
            return response()->json([
                'success' => false,
                'message' => 'Este tipo de lavado ya está activado en esta sucursal.',
            ]);
        }


        // Crear servicio de lavado
        $service = Service::create([
            'name' => $validated['name'],
            'price_net' => $validated['price_net'],
            'type_service' => 'car_wash',
            'id_branch_office' => $validated['id_branch_office'],
            'status' => 'available',
        ]);
        CarWash::create([
        'id_service' => $service->id_service
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Servicio de lavado creado correctamente.',
            'data' => $service
        ]);
    }
    public function disable($id)
    {
        $service = Service::findOrFail($id);

        // Solo cambia el estado
        $service->update(['status' => 'not_available']);

        return response()->json([
            'success' => true,
            'message' => 'Servicio de lavado desactivado correctamente.',
        ]);
    }

    public function show($branchOfficeId)
{
    $lavados = [
        'Lavado Auto Pequeño',
        'Lavado Auto Mediano',
        'Lavado Auto Grande',
    ];

    $registrados = Service::where('id_branch_office', $branchOfficeId)
        ->where('type_service', 'car_wash')
        ->where('status', 'available')
        ->get()
        ->keyBy('name');

    return view('tenant.admin.carwash.create', compact('lavados', 'registrados', 'branchOfficeId'));
}
public function edit($id)
{
    $lavado = Service::findOrFail($id);
    return view('tenant.admin.carwash.edit', compact('lavado'));
}
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price_net' => 'required|numeric|min:0',
    ]);

    // 1. Buscar el servicio original
    $lavadoAnterior = Service::findOrFail($id);

    // 2. Marcarlo como inactivo (no lo eliminamos)
    $lavadoAnterior->update(['status' => 'not_available']);

    // 3. Crear nuevo servicio con mismo nombre y nueva tarifa
    $nuevoLavado = Service::create([
        'name'             => $validated['name'],
        'price_net'        => $validated['price_net'],
        'type_service'     => 'car_wash',
        'id_branch_office' => $lavadoAnterior->id_branch_office,
        'status'           => 'available',
    ]);

    // 4. Registrar en historial (si usas car_washes)
    \App\Models\CarWash::create([
        'id_service' => $nuevoLavado->id_service
    ]);

    return redirect()
        ->route('lavados.show', $nuevoLavado->id_branch_office)
        ->with('success', 'Lavado actualizado correctamente. El precio anterior fue guardado como inactivo.');
}
public function history(Request $request)
{
    if ($request->ajax()) {
        $registros = ParkingRegister::whereNotNull('id_service')
            ->with('park.park_car') // para obtener patente
            ->get()
            ->map(function ($reg) {
                $car = $reg->park?->park_car;
                $service = Service::find($reg->id_service);

                return [
                    'id_parking_register' => $reg->id_parking_register,
                    'patent'     => $car?->patent ?? 'N/D',
                    'wash_type'  => $service?->name ?? 'Desconocido',
                    'washed'     => $reg->washed ? 'Sí' : 'No',
                    'price_net'  => $service?->price_net ?? 0,
                ];
            });

        return response()->json(['data' => $registros]);
    }

    return view('tenant.admin.carwash.history');
}
public function markAsWashed($id)
    {
        $registro = ParkingRegister::findOrFail($id);

        if (! $registro->washed) {
            $registro->washed = true;
            $registro->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Vehículo marcado como lavado correctamente.'
        ]);
    }




}
