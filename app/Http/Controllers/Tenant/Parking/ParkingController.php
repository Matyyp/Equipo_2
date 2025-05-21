<?php

namespace App\Http\Controllers\Tenant\Parking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\ModelCar;
use App\Models\Car;
use App\Models\Service;
use App\Models\Belong;
use App\Models\Park;
use App\Models\Parking;
use App\Models\Register;
use App\Models\Owner;
use App\Models\BranchOffice;
use App\Models\ParkingRegister;
use App\Models\DailyContract;
use App\Models\AnnualContract;
use App\Models\Generate;
use Carbon\Carbon;
use App\Models\PaymentRecord;
use App\Models\Payment;
use App\Models\Voucher;
use App\Models\Business;

use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;



use Yajra\DataTables\Facades\DataTables;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index(Request $request)
{
    if ($request->ajax()) {
        $user = auth()->user();

        $data = ParkingRegister::all()
            ->filter(function ($reg) {
                $park = Park::find($reg->id_park);
                return $park && $park->status === 'parked';
            })
            ->map(function ($reg) use ($user) {
                $park = Park::find($reg->id_park);
                $car = $park?->park_car;
                $owner = $car?->car_belongs->first()?->belongs_owner;
                $brand = $car?->car_brand?->name_brand;
                $model = $car?->car_model?->name_model;
                $service = Service::with('service_branch_office')->find($park?->id_service); 
                $branch = $service?->service_branch_office;

                if (!$service) return null;

                return [
                    'owner_name'    => $owner?->name ?? '-',
                    'patent'        => $car?->patent ?? '-',
                    'brand_model'   => trim(($brand ?? '') . ' ' . ($model ?? '')),
                    'start_date'    => $reg->start_date ? \Carbon\Carbon::parse($reg->start_date)->format('d-m-Y') : '[NULO]',
                    'end_date'      => $reg->end_date ? \Carbon\Carbon::parse($reg->end_date)->format('d-m-Y') : '[NULO]',
                    'days'          => $reg->days,
                    'washed' => $reg->id_service !== null,
                    'service_price' => number_format($service->price_net, 0, ',', '.'),
                    'total_value'   => $reg->total_value,
                    'total_formatted' => number_format($reg->total_value, 0, ',', '.'),
                    'id_parking_register' => $reg->getKey(),
                    'branch_name'   => $user->hasRole('SuperAdmin') ? $branch?->name_branch_offices ?? 'N/D' : null,
                ];
            })
            ->filter();

        return DataTables::of($data)->toJson();
    }

    $empresaExiste = Business::exists();
    $sucursalExiste = BranchOffice::exists();

    return view('tenant.admin.parking.index', compact('empresaExiste', 'sucursalExiste'));
}


     public function search(Request $request)
     {
         $plate = $request->input('plate');
     
         // Buscar si el auto está actualmente estacionado (status = 'parked')
         $park = Park::where('status', 'parked')
             ->whereHas('park_car', fn($q) => $q->where('patent', $plate))
             ->with([
                 'park_car.car_belongs.belongs_owner',
                 'park_car.car_brand',
                 'park_car.car_model'
             ])
             ->first();
     
         if ($park) {
             $car = $park->park_car;
             $owner = $car->car_belongs->first()?->belongs_owner;
     
             return response()->json([
                 'found' => true,
                 'name' => $owner?->name,
                 'phone' => $owner?->number_phone,
                 'brand' => $car->car_brand->name_brand,
                 'model' => $car->car_model->name_model,
                 'parked' => true
             ]);
         }
     
         // Si no está estacionado, buscar auto por patente
         $car = Car::where('patent', $plate)
             ->with(['car_belongs.belongs_owner', 'car_brand', 'car_model'])
             ->first();
     
         if ($car) {
             $owner = $car->car_belongs->first()?->belongs_owner;
     
             return response()->json([
                 'found' => true,
                 'name' => $owner?->name,
                 'phone' => $owner?->number_phone,
                 'brand' => $car->car_brand->name_brand,
                 'model' => $car->car_model->name_model,
                 'parked' => false
             ]);
         }
     
         return response()->json(['found' => false]);
     }
     
     
    public function data(Request $request)
    {
        $query = Parking::with(['brand','model']); // relación Eloquent

        return DataTables::of($query)
            ->addColumn('brand', function($row){
                return optional($row->brand)->name_brand;
            })
            ->addColumn('model', function($row){
                return optional($row->model)->name_model;
            })
            ->editColumn('wash_service', function($row){
                return $row->wash_service ? 'Sí' : 'No';
            })
            ->addColumn('actions', function($row){
                $edit = "<a href=\"".route('estacionamiento.edit', $row->id)."\" class=\"btn btn-sm btn-primary\">Editar</a>";
                $del  = "<form method=\"POST\" action=\"".route('estacionamiento.destroy', $row->id)."\" style=\"display:inline;\">"
                      . csrf_field()
                      . method_field('DELETE')
                      . "<button class=\"btn btn-sm btn-danger\" onclick=\"return confirm('¿Seguro?')\">Borrar</button>"
                      ."</form>";
                return $edit.' '.$del;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function checkContrato(Request $request)
    {
        $service = Service::with('service_branch_office.branch_office_contract')
            ->find($request->service_id);

        if (!$service || !$service->service_branch_office) {
            return response()->json(['contract_exists' => false]);
        }

        $contracts = $service->service_branch_office->branch_office_contract;

        // Tomamos el primero porque solo hay uno
        $contract = $contracts->first();

        if (!$contract) {
            return response()->json(['contract_exists' => false]);
        }

        // Verificamos según el tipo del servicio
        $exists = match ($service->type_service) {
            'parking_daily'  => DailyContract::where('id_contract', $contract->id_contract)->exists(),
            'parking_annual' => AnnualContract::where('id_contract', $contract->id_contract)->exists(),
            default          => false,
        };

        return response()->json(['contract_exists' => $exists]);
    }


    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    $user = auth()->user();
    // Consulta base para servicios
    $query = Service::with('service_branch_office.branch_office_contract')
        ->whereIn('type_service', ['parking_daily', 'parking_annual'])
        ->where('status', 'available');

    // Si no es Admin, limitar por sucursal
    if (!$user->hasRole('SuperAdmin')) {
        $query->where('id_branch_office', $user->id_branch_office);
    }

    $parkingServices = $query->get();

    $hasContract = false;

    foreach ($parkingServices as $svc) {
        $contract = $svc->service_branch_office->branch_office_contract->first();

        if ($contract) {
            if (
                ($svc->type_service === 'parking_daily' && DailyContract::where('id_contract', $contract->id_contract)->exists()) ||
                ($svc->type_service === 'parking_annual' && AnnualContract::where('id_contract', $contract->id_contract)->exists())
            ) {
                $hasContract = true;
                break;
            }
        }
    }

    $parks = Park::with('park_car')->get();

    // Cargar todas las sucursales si es Admin
    $branches = $user->hasRole('SuperAdmin')
        ? BranchOffice::all()
        : null;


    return view('tenant.admin.parking.create', compact('parkingServices', 'parks', 'hasContract', 'branches'));
}


    
    
    

    public function searchPhone(Request $request)
    {
        $request->validate(['phone' => 'required|string|max:9']);

        $owner = Owner::where('number_phone', $request->phone)->first();

        if ($owner) {
            return response()->json([
                'found' => true,
                'name'  => $owner->name,
            ]);
        }

        return response()->json(['found' => false]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'plate'        => 'required|string|max:8',
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:9',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'arrival_km'   => 'nullable|integer|min:0',
            'km_exit'      => 'nullable|integer|min:0',
            'brand_name'   => 'required|string|max:100',
            'model_name'   => 'required|string|max:100',
            'service_id'   => 'required|exists:services,id_service',
            'wash_type'    => 'nullable|exists:services,id_service',
        ]);
    
        $brandName = Str::title(trim($data['brand_name']));
        $brand = Brand::all()->first(function ($b) use ($brandName) {
            return levenshtein(strtolower($b->name_brand), strtolower($brandName)) <= 2;
        }) ?? Brand::create(['name_brand' => $brandName]);

        $modelName = Str::title(trim($data['model_name']));
        $model = ModelCar::all()->first(function ($m) use ($modelName) {
            return levenshtein(strtolower($m->name_model), strtolower($modelName)) <= 2;
        }) ?? ModelCar::create(['name_model' => $modelName]);
    
        $owner = Owner::firstOrCreate(
            ['number_phone' => $data['phone']],
            ['name' => $data['name'], 'type_owner' => 'cliente']
        );
    
        $car = Car::firstOrCreate(
            ['patent' => strtoupper($data['plate'])],
            ['id_brand' => $brand->id_brand, 'id_model' => $model->id_model]
        );
    
        Belong::firstOrCreate([
            'id_car' => $car->id_car,
            'id_owner' => $owner->id_owner
        ]);
    
        $service = Service::findOrFail($data['service_id']);
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $days = $start->diffInDays($end) + 1;
        $total = $days * $service->price_net;
    
        $park = Park::create([
            'id_car'     => $car->id_car,
            'id_service' => $data['service_id']
        ]);
    
        $parking = ParkingRegister::create([
            'id_brand'     => $brand->id_brand,
            'id_model'     => $model->id_model,
            'start_date'   => $data['start_date'],
            'end_date'     => $data['end_date'],
            'arrival_km'   => $data['arrival_km'] ?? null,
            'km_exit'      => $data['km_exit'] ?? null,
            'days'         => $days,
            'total_value'  => $total,
            'id_park'      => $park->id,    
            'status'       => 'unpaid',
            'washed'       => '0',
            'id_service'   => $request->input('wash_type'),
        ]);
    
        Register::create([
            'id_service'          => $data['service_id'],
            'id_parking_register' => $parking->id_parking_register
        ]);
        $tipo_servicio = $service->type_service;
        if ($tipo_servicio === 'parking_daily') {
            $id_contract = DailyContract::select('id_contract')->first();
        } elseif ($tipo_servicio === 'parking_annual') {
            $id_contract = AnnualContract::select('id_contract')->first();
        }
    
        Generate::create([
            'id_contract'          => $id_contract->id_contract,
            'id_parking_register'  => $parking->id_parking_register
        ]);
    
        return redirect()->route('estacionamiento.index')->with('success', 'Registro creado correctamente');
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
    public function edit($id)
{
    $user = auth()->user();

    $parking = ParkingRegister::findOrFail($id);

    $park = Park::with([
        'park_car.car_belongs.belongs_owner',
        'park_car.car_brand',
        'park_car.car_model',
    ])->find($parking->id_park);

    if (!$park) {
        abort(404, 'No se encontró el parque asociado al registro.');
    }

    $car = $park->park_car;
    $brands = optional($car->car_brand)->name_brand ?? 'Sin marca';
    $models = optional($car->car_model)->name_model ?? 'Sin modelo';
    $owner = optional($car->car_belongs->first())->belongs_owner ?? null;

    $service = Service::find($park->id_service);

    // Consulta base para servicios de estacionamiento
    $query = Service::with('service_branch_office.branch_office_contract')
        ->whereIn('type_service', ['parking_daily', 'parking_annual'])
        ->where('status', 'available');

    // SuperAdmin puede ver todos, otros solo su sucursal
    if (!$user->hasRole('SuperAdmin')) {
        $query->where('id_branch_office', $park->id_branch_office);
    }

    $parkingServices = $query->get();

    // Verifica si hay al menos un contrato activo
    $hasContract = false;
    foreach ($parkingServices as $svc) {
        $contract = $svc->service_branch_office->branch_office_contract->first();

        if ($contract) {
            $exists = $svc->type_service === 'parking_daily'
                ? \App\Models\DailyContract::where('id_contract', $contract->id_contract)->exists()
                : \App\Models\AnnualContract::where('id_contract', $contract->id_contract)->exists();

            if ($exists) {
                $hasContract = true;
                break;
            }
        }
    }

    // Lavados disponibles
    $carWashServices = Service::where('type_service', 'car_wash')
        ->where('status', 'available')
        ->where('id_branch_office', $park->id_branch_office)
        ->get();

    // Verificar si ya hay un lavado asignado
    $lavadoAsignado = null;
    if ($parking->id_service) {
        $lavado = Service::find($parking->id_service);
        if ($lavado && $lavado->type_service === 'car_wash') {
            $lavadoAsignado = $lavado->id_service;
        }
    }

    // SuperAdmin puede seleccionar sucursal
    $branches = $user->hasRole('SuperAdmin')
        ? BranchOffice::where('status', 'active')->get()
        : [];

    return view('tenant.admin.parking.edit', compact(
        'parking', 'car', 'owner', 'service', 'brands', 'models',
        'parkingServices', 'carWashServices', 'lavadoAsignado',
        'branches', 'hasContract'
    ));
}



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $user = auth()->user();

    $data = $request->validate([
        'plate'            => 'required|string|max:8',
        'name'             => 'required|string|max:255',
        'phone'            => 'required|string|max:9',
        'start_date'       => 'required|date',
        'end_date'         => 'required|date|after_or_equal:start_date',
        'arrival_km'       => 'nullable|integer|min:0',
        'km_exit'          => 'nullable|integer|min:0',
        'brand_name'       => 'required|string|max:255',
        'model_name'       => 'required|string|max:255',
        'wash_type'        => 'nullable|exists:services,id_service',
        'wash_service'     => 'nullable|boolean',
        'service_id'       => 'required|exists:services,id_service',
        'branch_office_id' => 'nullable|exists:branch_offices,id_branch',
    ]);

    DB::transaction(function () use ($data, $id, $user, $request) {
        $parking = ParkingRegister::findOrFail($id);
        $park = Park::with('park_car.car_belongs.belongs_owner')->findOrFail($parking->id_park);
        $car = $park->park_car;
        $owner = $car->car_belongs->first()->belongs_owner;

        // Actualizar dueño
        $owner->update([
            'name' => $data['name'],
            'number_phone' => $data['phone']
        ]);

        // Marca
        $brand = Brand::firstOrCreate([
            'name_brand' => Str::title($data['brand_name'])
        ]);
        $car->id_brand = $brand->id_brand;

        // Modelo
        $model = ModelCar::firstOrCreate([
            'name_model' => Str::title($data['model_name'])
        ]);
        $car->id_model = $model->id_model;

        $car->save();

        // Si es SuperAdmin, puede cambiar el tipo de estacionamiento
        if ($user->hasRole('SuperAdmin')) {
            $park->id_service = $data['service_id'];
            $park->save();
        }

        $startDate = Carbon::parse($data['start_date']);
        $endDate   = Carbon::parse($data['end_date']);
        $days      = $startDate->diffInDays($endDate) + 1;

        $service = Service::find($park->id_service);
        $total   = $days * ($service?->price_net ?? 0);

        // Asignar lavado si fue solicitado
        $washServiceId = null;
        $washServiceInput = $request->input('wash_service');
        $washTypeInput = $request->input('wash_type');

        if ($washServiceInput && $washTypeInput) {
            $selectedWash = Service::find($washTypeInput);
            if ($selectedWash && $selectedWash->type_service === 'car_wash') {
                $washServiceId = $selectedWash->id_service;
            }
        }


        $parking->update([
            'start_date'   => $startDate,
            'end_date'     => $endDate,
            'arrival_km'   => $data['arrival_km'] ?? null,
            'km_exit'      => $data['km_exit'] ?? null,
            'days'         => $days,
            'total_value'  => $total,
            'washed' => $washServiceId ? false : null,// no marcar como lavado aún
            'id_service'   => $washServiceId,    // puede ser null si no hay lavado
        ]);
    });

    return redirect()->route('estacionamiento.index')->with('success', 'Registro actualizado correctamente.');
}



public function history(Request $request)
{
    if ($request->ajax()) {
        $user = auth()->user();

        $parks = Park::where('status', 'not_parked')
            ->with([
                'park_car.car_brand',
                'park_car.car_model',
                'park_car.car_belongs.belongs_owner',
                'park_parking.parking_service.service_branch_office',
                'park_parking.parking_register.register_parking_register',
            ])
            ->get();

        $rows = $parks->map(function ($park) use ($user) {
            $car     = $park->park_car;
            $owner   = optional(optional($car)->car_belongs->first())->belongs_owner;
            $pivot   = $park->park_parking;
            $service = optional($pivot)->parking_service;
            $branch  = $service?->service_branch_office;

            if (!$service) return null;

            $register = collect(optional($pivot)->parking_register)->first(function ($item) {
                return $item?->register_parking_register?->status === 'paid';
            });

            $reg = $register?->register_parking_register;
            if (!$reg) return null;

            return [
                'owner_name'          => $owner?->name ?? '-',
                'owner_phone'         => $owner?->number_phone ?? '-',
                'patent'              => $car?->patent ?? '-',
                'brand'               => $car?->car_brand?->name_brand ?? '-',
                'model'               => $car?->car_model?->name_model ?? '-',
                'start_date'          => $reg->start_date ? \Carbon\Carbon::parse($reg->start_date)->format('d-m-Y') : '-',
                'end_date'            => $reg->end_date ? \Carbon\Carbon::parse($reg->end_date)->format('d-m-Y') : '-',
                'days'                => $reg->days ?? '-',
                'price'               => $service->price_net ?? 0,
                'total_value'         => $reg->total_value ?? 0,
                'id_parking_register' => $register->id_parking_register,
                'branch_name'         => $user->hasRole('SuperAdmin') ? $branch?->name_branch_offices ?? 'N/D' : null,
                'washed' => $reg?->id_service !== null,
            ];
        })->filter()->values();
               

        return response()->json(['data' => $rows]);
    }

    return view('tenant.admin.parking.history');
}

    

    public function print($parkingId)
    {
        $parking = ParkingRegister::with([
            'parking_register_register.register_parking.parking_service.service_branch_office.branch_office_business',
            'parking_register_generates.generates_contract_parking.contract_parking_contract.contract_presents.presents_contact_information',
            'parking_register_generates.generates_contract_parking.contract_parking_contract.contract_contains.contains_rule',
        ])->findOrFail($parkingId);
    
        $client = Park::where('id', $parking->id_park)
            ->with([
                'park_car.car_belongs.belongs_owner',
                'park_car.car_model',
                'park_car.car_brand'
            ])
            ->firstOrFail();
    
        // Logo
        $logoNombre = $parking->parking_register_register
            ->register_parking
            ->parking_service
            ->service_branch_office
            ->branch_office_business
            ->logo ?? 'logo_empresa.png';
    
        $logoPath = public_path('storage/tenants/' . request()->getHost() . '/imagenes/' . $logoNombre);
    
        // Datos de contacto
        $datosContacto = $parking->parking_register_generates
            ->flatMap(fn($gen) =>
                $gen->generates_contract_parking?->contract_parking_contract?->contract_presents ?? []
            )
            ->flatMap(fn($present) =>
                $present->presents_contact_information ? [$present->presents_contact_information] : []
            );
    
        // Reglas del contrato
        $reglas = $parking->parking_register_generates
            ->flatMap(fn($gen) =>
                $gen->generates_contract_parking?->contract_parking_contract?->contract_contains ?? []
            )
            ->map(fn($contain) => $contain->contains_rule);
    
        // PDF
        $pdfContent = PDF::loadView('pdf.ContractDaily', [
            'nombre'                => $client->park_car->car_belongs->first()?->belongs_owner->name ?? 'No disponible',
            'telefono'              => $client->park_car->car_belongs->first()?->belongs_owner->number_phone ?? 'No disponible',
            'patente'               => $client->park_car->patent ?? 'No disponible',
            'marca'                 => $client->park_car->car_brand->name_brand ?? 'No disponible',
            'modelo'                => $client->park_car->car_model->name_model ?? 'No disponible',
            'inicio'  => Carbon::parse($parking->start_date)->format('d-m-Y'),
            'termino' => Carbon::parse($parking->end_date)->format('d-m-Y'),
            'dias'                  => $parking->days,
            'valor_total'           => $parking->total_value,
            'url_logo'              => $logoPath,
            'direccion_sucursal'    => $parking->parking_register_register->register_parking->parking_service->service_branch_office->street ?? 'No disponible',
            'valor_estacionamiento' => $parking->parking_register_register->register_parking->parking_service->price_net ?? 'No disponible',
            'dato_contacto'         => $parking->parking_register_register->register_parking->parking_service->service_branch_office->contact_info ?? 'No disponible',
            'horario'               => $parking->parking_register_register->register_parking->parking_service->service_branch_office->schedule ?? 'No disponible',
            'dueño'                 => $parking->parking_register_register->register_parking->parking_service->service_branch_office->branch_office_business->owner_business ?? 'No disponible',
            'rut'                   => $parking->parking_register_register->register_parking->parking_service->service_branch_office->branch_office_business->owner_rut ?? 'No disponible',
            'datos_contacto'        => $datosContacto,
            'reglas'                => $reglas,
        ])
        ->setPaper('a4', 'portrait')
        ->output();
    
        $pdfBase64 = base64_encode($pdfContent);
    
        return view('pdf.print_contrato', compact('pdfBase64'));
    }



    public function printTicket($parkingId)
    {
        $parking = ParkingRegister::with([
            'parking_register_register.register_parking.parking_service.service_branch_office.branch_office_business',
            'parking_register_generates.generates_contract_parking.contract_parking_contract.contract_presents.presents_contact_information',
        ])->findOrFail($parkingId);

        $client = Park::where('id', $parking->id_park)
            ->with([
                'park_car.car_belongs.belongs_owner',
                'park_car.car_model',
                'park_car.car_brand'
            ])
            ->firstOrFail();

        // Datos base
        $nombre   = $client->park_car->car_belongs->first()?->belongs_owner->name ?? 'No disponible';
        $telefono = $client->park_car->car_belongs->first()?->belongs_owner->number_phone ?? 'No disponible';
        $marca    = $client->park_car->car_brand->name_brand ?? 'No disponible';
        $modelo   = $client->park_car->car_model->name_model ?? 'No disponible';
        $patente  = $client->park_car->patent ?? 'No disponible';

        // Fechas formateadas
        $inicio  = Carbon::parse($parking->start_date)->format('d-m-Y');
        $termino = Carbon::parse($parking->end_date)->format('d-m-Y');

        // Generar PDF tipo ticket horizontal
        $pdfContent = PDF::loadView('pdf.TicketParking', [
            'nombre'   => $nombre,
            'telefono' => $telefono,
            'marca'    => $marca,
            'modelo'   => $modelo,
            'patente'  => $patente,
            'inicio'   => $inicio,
            'termino'  => $termino,
        ])
        ->setPaper([0, 0, 300, 125]) // ~10.5 cm ancho x ~4.4 cm alto
        ->output();

        $pdfBase64 = base64_encode($pdfContent);

        return view('pdf.print_contrato', compact('pdfBase64'));
    }

    public function checkout(Request $request)
{
    $request->validate([
        'type_payment'         => 'required|string',
        'id_parking_register'  => 'required|exists:parking_registers,id_parking_register',
    ]);

    $parking = ParkingRegister::with('park.service')->findOrFail($request->id_parking_register);

    if (! $parking->park) {
        return back()->withErrors('No hay parqueo asociado.');
    }

    if (! $parking->park->service) {
        return back()->withErrors('No hay servicio asociado.');
    }

    DB::transaction(function () use ($request, $parking) {
        $serviceId = $parking->park->service->id_service;

        $payment = Payment::create([
            'id_service' => $serviceId,
            'id_voucher' => null,
        ]);

        $paymentRecord = PaymentRecord::create([
            'id_payment'          => $payment->id,
            'id_service'          => $serviceId,
            'id_parking_register' => $request->id_parking_register,
            'type_payment'        => $request->type_payment,
            'amount'              => $parking->total_value,
            'payment_date'        => now(),
            'id_voucher'          => null,
        ]);

        $voucher = Voucher::create([
            'code'         => random_int(100000, 999999),
            'payment'      => $paymentRecord->type_payment,
            'amount'       => $paymentRecord->amount,
            'id_register'  => $paymentRecord->id_parking_register,
            'discount'     => 0, // Ajusta si hay lógica de descuento
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $payment->update([
            'id_voucher' => $voucher->id_voucher,
        ]);

        $paymentRecord->update([
            'id_voucher' => $voucher->id_voucher,
        ]);

        Park::where('id', $parking->id_park)->update(['status' => 'not_parked']);
        $parking->update(['status' => 'paid']);
    });

    return redirect()
        ->route('estacionamiento.index')
        ->with('success', 'Check-Out y pago registrados correctamente.');
}

public function getServicesByBranch(Request $request)
{
    $branchId = $request->input('branch_id');

    $services = Service::with('service_branch_office.branch_office_contract')
        ->where('id_branch_office', $branchId)
        ->where('status', 'available')
        ->whereIn('type_service', ['parking_daily', 'parking_annual'])
        ->get();

    $filteredServices = [];

    foreach ($services as $svc) {
        $contract = $svc->service_branch_office->branch_office_contract->first();

        if ($contract) {
            $hasValidContract = false;

            if ($svc->type_service === 'parking_daily') {
                $hasValidContract = DailyContract::where('id_contract', $contract->id_contract)->exists();
            }

            if ($svc->type_service === 'parking_annual') {
                $hasValidContract = AnnualContract::where('id_contract', $contract->id_contract)->exists();
            }

            if ($hasValidContract) {
                $filteredServices[] = [
                    'id_service' => $svc->id_service,
                    'name' => $svc->name,
                ];
            }
        }
    }

    return response()->json($filteredServices);
}


    

    
    
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

