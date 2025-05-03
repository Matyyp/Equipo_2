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
use App\Models\Register;
use App\Models\Owner;
use App\Models\BranchOffice;
use App\Models\ParkingRegister;
use App\Models\DailyContract;
use App\Models\AnnualContract;
use App\Models\Generate;
use Carbon\Carbon;
use App\Models\PaymentRecord;
use Barryvdh\DomPDF\Facade\Pdf;



use Yajra\DataTables\Facades\DataTables;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
     {
         if ($request->ajax()) {
             // Obtenemos todos y luego filtramos los que tienen Park válido
             $data = ParkingRegister::all()
                 ->filter(function ($reg) {
                     $park = Park::find($reg->id_park);
                     return $park && $park->deleted_at === null;
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
                        'start_date' => $reg->start_date ? \Carbon\Carbon::parse($reg->start_date)->format('d-m-Y') : '[NULO]',
                        'end_date'   => $reg->end_date ? \Carbon\Carbon::parse($reg->end_date)->format('d-m-Y') : '[NULO]',
                         'days'          => $reg->days,
                         'service_price' => number_format($service?->price_net ?? 0, 0, ',', '.'),
                         'total_value'   => number_format($reg->total_value, 0, ',', '.'),
                         'id_parking_register' => $reg->getKey(),
                     ];
                 });
     
             // Convertimos a una colección paginable para que funcione con DataTables
             return DataTables::of(collect($data))->toJson();
         }
     
         return view('tenant.admin.parking.index');
     }

     public function search(Request $request)
     {
         $plate = $request->input('plate');
     
         $parks = Park::with('park_car')->get();
     
         $park = $parks->first(function ($p) use ($plate) {
             return $p->park_car && $p->park_car->patent === $plate && !$p->checked_out;
         });
     
         if ($park) {
             $car = $park->park_car;
             $owner = $car->car_belongs->first()?->belongs_owner;
             return response()->json([
                 'found' => true,
                 'name' => $owner?->name,
                 'phone' => $owner?->phone,
                 'id_brand' => $car->id_brand,
                 'id_model' => $car->id_model,
                 'parked' => true // 👈 indica que ya está estacionado
             ]);
         }
     
         // Si no está estacionado, pero existe el auto:
         $car = \App\Models\Car::where('patent', $plate)->first();
         if ($car) {
             $owner = $car->car_belongs->first()?->belongs_owner;
             return response()->json([
                 'found' => true,
                 'name' => $owner?->name,
                 'phone' => $owner?->phone,
                 'id_brand' => $car->id_brand,
                 'id_model' => $car->id_model,
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
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands          = Brand::all();
        $models          = ModelCar::all();
        $parkingServices = Service::whereIn('type_service', ['parking_daily', 'parking_annual'])->get();
        $parks = Park::with('park_car')->get();

    
        return view('tenant.admin.parking.create', compact(
            'brands',
            'models',
            'parkingServices',
            'parks'
        ));
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
            'id_brand'     => 'required|exists:brands,id_brand',
            'id_model'     => 'required|exists:model_cars,id_model',
            'service_id'   => 'required|exists:services,id_service',
            //'wash_service' => 'nullable|boolean',
        ]);

        $owner = Owner::firstOrCreate(
            ['number_phone' => $data['phone']],
            [
                'name'       => $data['name'],
                'type_owner' => 'cliente'
            ]
        );

        $car = Car::firstOrCreate(
            ['patent' => strtoupper($data['plate'])],
            [
                'id_brand' => $data['id_brand'],
                'id_model' => $data['id_model']
            ]
        );

        Belong::create([
            'id_car' => $car->id_car,
            'id_owner' => $owner->id_owner
        ]);

        $service = Service::findOrFail($data['service_id']);

        $start = Carbon::parse($data['start_date']);
        $end   = Carbon::parse($data['end_date']);
        $days  = $start->diffInDays($end) + 1;
    
        $total = $days * $service->price_net; 
        
        $park = Park::create([
            'id_car' => $car->id_car,
            'id_service' => $data['service_id']
        ]);

        $parking = ParkingRegister::create([
            'id_brand'     => $data['id_brand'],
            'id_model'     => $data['id_model'],
            'start_date'   => $data['start_date'],
            'end_date'     => $data['end_date'],
            'arrival_km'   => $data['arrival_km'] ?? null,
            'km_exit'      => $data['km_exit']    ?? null,
            'days'        => $days,
            'total_value' => $total,
            'id_park' => $park->id
        ]);

        Register::create([
            'id_service' => $data['service_id'],
            'id_parking_register' => $parking->id_parking_register
        ]);

        $tipo_servicio = Service::select('type_service')
        ->where('id_service', $data['service_id'])
        ->first()?->type_service;
    

        if($tipo_servicio == 'parking_daily'){
            $id_contract = DailyContract::select('id_contract')->first();;

        }else if($tipo_servicio == 'parking_annual'){
            $id_contract = AnnualContract::select('id_contract')->first();;
        }

        Generate::create([
            'id_contract' => $id_contract->id_contract,
            'id_parking_register' => $parking->id_parking_register
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
        $owner    = $car->car_belongs->first()->belongs_owner;
        $service  = $park->parking_service;

        $brands          = Brand::all();
        $models          = ModelCar::all();
        $parkingServices = Service::where('type_service','parking')->get();

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
            'phone'        => 'required|string|max:20',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'arrival_km'   => 'nullable|integer|min:0',
            'km_exit'      => 'nullable|integer|min:0',
            'id_brand'     => 'required|exists:brands,id_brand',
            'id_model'     => 'required|exists:model_cars,id_model',
            'service_id'   => 'required|exists:services,id_service',
            'wash_service' => 'sometimes|boolean',
        ]);
    
        $parking = ParkingRegister::findOrFail($id);
    
        $linkReg   = $parking->parking_register_register()->first();     
        $parkEntry = $linkReg?->register_parking;                        
        $pivotPark = $parkEntry?->parking_park()->first();                
        $car       = $pivotPark?->park_car;                              
        $ownerRel  = $car->car_belongs()->first()->belongs_owner;         
    
        $car->update([
            'patent'   => $data['plate'],
            'id_brand' => $data['id_brand'],
            'id_model' => $data['id_model'],
        ]);
    
        $ownerRel->update([
            'name'         => $data['name'],
            'number_phone' => $data['phone'],
        ]);
    
        $parking->update([
            'start_date'   => $data['start_date'],
            'end_date'     => $data['end_date'],
            'arrival_km'   => $data['arrival_km'] ?? null,
            'km_exit'      => $data['km_exit']    ?? null,
            'wash_service' => ! empty($data['wash_service']),
        ]);
    
        $start   = Carbon::parse($data['start_date']);
        $end     = Carbon::parse($data['end_date']);
        $days    = $start->diffInDays($end) + 1;
        $service = Service::findOrFail($data['service_id']);
        $total   = $days * $service->price_net;
    
        $parking->update([
            'days'        => $days,
            'total_value' => $total,
        ]);
    
        Register::updateOrCreate(
            ['id_parking_register' => $parking->id_parking_register],
            ['id_service'         => $data['service_id']]
        );
    
        return redirect()
            ->route('estacionamiento.index')
            ->with('success', 'Ingreso actualizado correctamente.');
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {

            $parks = Park::onlyTrashed()
                ->with([
                    'park_car.car_brand',
                    'park_car.car_model',
                    'park_car.car_belongs.belongs_owner',
                    'park_parking.parking_service',
                    'park_parking.parking_register' => function ($q) {
                        $q->with('register_parking_register');
                    },
                ])
                ->orderBy('deleted_at', 'desc')
                ->get();
    
            $rows = $parks->map(function ($park) {
                $car     = $park->park_car;
                $owner   = optional(optional($car)->car_belongs->first())->belongs_owner;
                $pivot   = $park->park_parking;
                $service = optional($pivot)->parking_service;
                $reg     = optional(optional($pivot)->parking_register[0])->register_parking_register;
    
                return [
                    'owner_name'   => $owner?->name ?? '-',
                    'owner_phone'  => $owner?->number_phone ?? '-',
                    'patent'       => $car?->patent ?? '-',
                    'brand'        => $car?->car_brand?->name_brand ?? '-',
                    'model'        => $car?->car_model?->name_model ?? '-',
                    'start_date'   => $reg?->start_date ?? '-',
                    'end_date'     => $reg?->end_date ?? '-',
                    'days'         => $reg?->days ?? '-',
                    'price'        => $service?->price_net ?? '-',
                    'total_value'  => $reg?->total_value ?? '-',
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
        $pdfContent = \PDF::loadView('pdf.ContractDaily', [
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

    
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

