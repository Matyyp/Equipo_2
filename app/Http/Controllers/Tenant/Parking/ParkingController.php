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
        $data = ParkingRegister::all()
            ->filter(function ($reg) {
                $park = Park::find($reg->id_park);
                return $park && $park->status === 'parked';
            })
            ->map(function ($reg) {
                $park = Park::find($reg->id_park);
                $car = $park?->park_car;
                $owner = $car?->car_belongs->first()?->belongs_owner;
                $brand = $car?->car_brand?->name_brand;
                $model = $car?->car_model?->name_model;
                $service = Service::find($park?->id_service);

                return [
                    'owner_name'    => $owner?->name ?? '-',
                    'patent'        => $car?->patent ?? '-',
                    'brand_model'   => trim(($brand ?? '') . ' ' . ($model ?? '')),
                    'start_date'    => $reg->start_date ? \Carbon\Carbon::parse($reg->start_date)->format('d-m-Y') : '[NULO]',
                    'end_date'      => $reg->end_date ? \Carbon\Carbon::parse($reg->end_date)->format('d-m-Y') : '[NULO]',
                    'days'          => $reg->days,
                    'service_price' => number_format($service?->price_net ?? 0, 0, ',', '.'),
                    'total_value'   => $reg->total_value,
                    'total_formatted' => number_format($reg->total_value, 0, ',', '.'),
                    'id_parking_register' => $reg->getKey(),
                ];
            });

        return DataTables::of(collect($data))->toJson();
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
    
        $parkingServices = Service::with('service_branch_office.branch_office_contract') // eager loading para evitar N+1
            ->whereIn('type_service', ['parking_daily', 'parking_annual'])
            ->where('id_branch_office', $user->id_branch_office)
            ->where('status', 'available')
            ->get();
    
        $hasContract = false;
    
        foreach ($parkingServices as $svc) {
            $contract = $svc->service_branch_office->branch_office_contract->first(); // obtenemos un único contrato
    
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
    
        return view('tenant.admin.parking.create', compact('parkingServices', 'parks', 'hasContract'));
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
            'status'       => 'unpaid'
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
        $parking = ParkingRegister::with([
            'parking_register_register.register_parking.parking_park.park_car.car_belongs.belongs_owner',
            'parking_register_register.register_parking.parking_park.park_car.car_brand',
            'parking_register_register.register_parking.parking_park.park_car.car_model',
            'parking_register_register.register_parking.parking_service',
        ])->findOrFail($id);

        $link     = $parking->parking_register_register->first();
        $park     = $link->register_parking;
        $pivot    = $park->parking_park->first();
        $car      = $pivot->park_car;
        $brands   = $pivot->park_car->car_brand->name_brand;
        $models   = $pivot->park_car->car_model->name_model;;
        $owner    = $car->car_belongs->first()->belongs_owner;
        $service  = $park->parking_service;

        $parkingServices = Service::whereIn('type_service', ['parking_daily', 'parking_annual'])
        ->where('id_branch_office', auth()->user()->id_branch_office)
        ->get();

        return view('tenant.admin.parking.edit', compact(
            'parking','car','owner','service','brands','models','parkingServices'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'plate'        => 'required|string|max:8',
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:9',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'arrival_km'   => 'nullable|integer|min:0',
            'km_exit'      => 'nullable|integer|min:0',
            'brand_name'   => 'required|string|max:255',
            'model_name'   => 'required|string|max:255',
            'wash_service' => 'nullable|boolean'
        ]);
    
        DB::transaction(function () use ($data, $id) {
            $parking = ParkingRegister::with([
                'parking_register_register.register_parking.parking_park.park_car',
                'parking_register_register.register_parking.parking_service'
            ])->findOrFail($id);
    
            $link = $parking->parking_register_register->first();
            $park = $link->register_parking;
            $pivot = $park->parking_park->first();
            $car = $pivot->park_car;
    
            $owner = $car->car_belongs->first()->belongs_owner;
            $owner->update([
                'name' => $data['name'],
                'number_phone' => $data['phone']
            ]);
    
            // Marca
            $brand = Brand::where('name_brand', 'like', "%{$data['brand_name']}%")->first();
            if ($brand) {
                $brandCarsCount = Car::where('id_brand', $car->id_brand)->count();
                if ($brandCarsCount <= 1 && $car->id_brand != $brand->id_brand) {
                    Brand::where('id_brand', $car->id_brand)->delete();
                }
                $car->id_brand = $brand->id_brand;
            } else {
                $newBrand = Brand::create(['name_brand' => Str::title($data['brand_name'])]);
                $car->id_brand = $newBrand->id_brand;
            }
    
            // Modelo
            $model = ModelCar::where('name_model', 'like', "%{$data['model_name']}%")->first();
            if ($model) {
                $modelCarsCount = Car::where('id_model', $car->id_model)->count();
                if ($modelCarsCount <= 1 && $car->id_model != $model->id_model) {
                    ModelCar::where('id_model', $car->id_model)->delete();
                }
                $car->id_model = $model->id_model;
            } else {
                $newModel = ModelCar::create(['name_model' => Str::title($data['model_name'])]);
                $car->id_model = $newModel->id_model;
            }
    
            $car->save();
    
            // Cálculo de días y total
            $startDate = Carbon::parse($data['start_date']);
            $endDate   = Carbon::parse($data['end_date']);
            $days      = $startDate->diffInDays($endDate) + 1;
    
            // Accede al servicio a través del camino indirecto
            $service = optional($link->register_parking?->parking_service);
            $priceNet = $service?->price_net ?? 0;
            $total    = $days * $priceNet;
    
            // Actualiza ParkingRegister
            $parking->update([
                'start_date'   => $startDate,
                'end_date'     => $endDate,
                'arrival_km'   => $data['arrival_km'] ?? null,
                'km_exit'      => $data['km_exit'] ?? null,
                'days'         => $days,
                'total_value'  => $total,
            ]);
        });
    
        return redirect()->route('estacionamiento.index')->with('success', 'Registro actualizado correctamente.');
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            $parks = Park::with([
                'park_car.car_brand',
                'park_car.car_model',
                'park_car.car_belongs.belongs_owner',
                'park_parking.parking_service',
                'park_parking.parking_register' => function ($q) {
                    $q->with([
                        'register_parking_register' => function ($r) {
                            $r->where('status', 'paid');
                        }
                    ]);
                },
            ])->get();
                   
    
            $rows = $parks->map(function ($park) {
                $car     = $park->park_car;
                $owner   = optional(optional($car)->car_belongs->first())->belongs_owner;
                $pivot   = $park->park_parking;
                $service = optional($pivot)->parking_service;
    
                // Obtener el primer parking_register (si existe)
                $register = collect(optional($pivot)->parking_register)->first();
                $reg      = $register?->register_parking_register;
    
                return [
                    'owner_name'          => $owner?->name ?? '-',
                    'owner_phone'         => $owner?->number_phone ?? '-',
                    'patent'              => $car?->patent ?? '-',
                    'brand'               => $car?->car_brand?->name_brand ?? '-',
                    'model'               => $car?->car_model?->name_model ?? '-',
                    'start_date'          => $reg?->start_date,
                    'end_date'            => $reg?->end_date,
                    'days'                => $reg?->days,
                    'price'               => $service?->price_net,
                    'total_value'         => $reg?->total_value,
                    'id_parking_register' => $register?->id_parking_register, // necesario para el botón
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

    

    
    
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

