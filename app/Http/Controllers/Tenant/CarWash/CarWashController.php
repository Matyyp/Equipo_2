<?php

namespace App\Http\Controllers\Tenant\CarWash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\CarWash;
use App\Models\ParkingRegister;

class CarWashController extends Controller
{
    // Valida si el usuario tiene acceso a la sucursal
    protected function assertBranchAccess($branchOfficeId)
    {
        $user = auth()->user();
        if (!$user->hasRole('SuperAdmin') && $user->id_branch_office != $branchOfficeId) {
            abort(403, 'No tienes acceso a esta sucursal.');
        }
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $branchOfficeId = $user->hasRole('SuperAdmin')
            ? $request->input('id_branch_office')
            : $user->id_branch_office;

        $this->assertBranchAccess($branchOfficeId);

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
        $branchOfficeId = $user->hasRole('SuperAdmin')
            ? $request->input('id_branch_office')
            : $user->id_branch_office;

        $this->assertBranchAccess($branchOfficeId);

        $washServices = Service::where('id_branch_office', $branchOfficeId)
            ->where('type_service', 'car_wash')
            ->where('status', 'available')
            ->get();

        return view('tenant.admin.carwash.create', compact('washServices', 'branchOfficeId'));
    }

    public function getByBranch(Request $request)
{
    $branchOfficeId = $request->input('id_branch_office');

    $lavados = Service::where('id_branch_office', $branchOfficeId)
        ->where('type_service', 'car_wash')
        ->where('status', 'available')
        ->get(['id_service', 'name']);

    return response()->json($lavados);
}



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'price_net'         => 'required|numeric|min:0',
            'id_branch_office'  => 'required|exists:branch_offices,id_branch',
        ]);

        $this->assertBranchAccess($validated['id_branch_office']);

        $exists = Service::where('id_branch_office', $validated['id_branch_office'])
            ->where('type_service', 'car_wash')
            ->where('name', $validated['name'])
            ->where('status', 'available')
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Este tipo de lavado ya está activado en esta sucursal.',
            ]);
        }

        $service = Service::create([
            'name'             => $validated['name'],
            'price_net'        => $validated['price_net'],
            'type_service'     => 'car_wash',
            'id_branch_office' => $validated['id_branch_office'],
            'status'           => 'available',
        ]);

        CarWash::create([
            'id_service' => $service->id_service,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Servicio de lavado creado correctamente.',
            'data' => $service,
        ]);
    }

    public function disable($id)
    {
        $service = Service::findOrFail($id);
        $this->assertBranchAccess($service->id_branch_office);

        $service->update(['status' => 'not_available']);

        return response()->json([
            'success' => true,
            'message' => 'Servicio de lavado desactivado correctamente.',
        ]);
    }

    public function show($branchOfficeId)
    {
        $this->assertBranchAccess($branchOfficeId);

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
        $this->assertBranchAccess($lavado->id_branch_office);

        return view('tenant.admin.carwash.edit', compact('lavado'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'price_net'  => 'required|numeric|min:0',
        ]);

        $lavadoAnterior = Service::findOrFail($id);
        $this->assertBranchAccess($lavadoAnterior->id_branch_office);

        $lavadoAnterior->update(['status' => 'not_available']);

        $nuevoLavado = Service::create([
            'name'             => $validated['name'],
            'price_net'        => $validated['price_net'],
            'type_service'     => 'car_wash',
            'id_branch_office' => $lavadoAnterior->id_branch_office,
            'status'           => 'available',
        ]);

        CarWash::create([
            'id_service' => $nuevoLavado->id_service,
        ]);

        return redirect()
            ->route('lavados.show', $nuevoLavado->id_branch_office)
            ->with('success', 'Lavado actualizado correctamente. El precio anterior fue guardado como inactivo.');
    }

    public function history(Request $request)
{
    
    $user = auth()->user();
    if ($request->ajax()) {
        
        try {
            
            $registros = \App\Models\ParkingRegister::whereNotNull('id_service')
                ->with('park.park_car')
                ->get()
                ->map(function ($reg) use ($user) {
                    $car = $reg->park?->park_car;
                    $service = \App\Models\Service::find($reg->id_service);
                    $branch = $service?->service_branch_office;
                
                    return [
                        'id_parking_register' => $reg->id_parking_register,
                        'patent'              => $car?->patent ?? 'N/D',
                        'wash_type'           => $service?->name ?? 'Desconocido',
                        'washed'              => $reg->washed ? 'Sí' : 'No',
                        'price_net'           => $service?->price_net ?? 0,
                        'branch_name'         => $user->hasRole('SuperAdmin') ? $branch?->name_branch_offices ?? 'N/D' : null,
                    ];
                });


            return response()->json(['data' => $registros]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    $branches = $user->hasRole('SuperAdmin') ? \App\Models\BranchOffice::all() : [];

    return view('tenant.admin.carwash.history', compact('branches'));
}





    public function markAsWashed($id)
    {
        $registro = ParkingRegister::findOrFail($id);

        if (!$registro->washed) {
            $registro->washed = true;
            $registro->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Vehículo marcado como lavado correctamente.',
        ]);
    }
}
