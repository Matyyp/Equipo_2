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
use App\Models\ParkingRegister;
use Carbon\Carbon;
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
            $query = ParkingRegister::query()
                ->whereHas('parking_register_register.register_parking', function($q) {
                    $q->whereNull('deleted_at'); 
                })
                ->with([
                    'parking_register_register.register_parking.parking_service',
                    'parking_register_register.register_parking.parking_park',
                    'parking_register_register.register_parking.parking_park.park_car.car_belongs.belongs_owner',
                    'parking_register_register.register_parking.parking_park.park_car.car_brand',
                    'parking_register_register.register_parking.parking_park.park_car.car_model',
                ]);
    
            $rows = $query->get()->map(function($reg) {
                $linkReg   = $reg->parking_register_register->first();
                $parkEntry = $linkReg?->register_parking;
                $pivotPark = $parkEntry?->parking_park->first();
                $car       = $pivotPark?->park_car;
                $owner     = $car?->car_belongs->first()?->belongs_owner;
                $service   = $parkEntry?->parking_service;
    
                return [
                    'owner_name'          => $owner?->name               ?? '-',
                    'patent'              => $car?->patent               ?? '-',
                    'brand_model'         => trim(
                                              ($car->car_brand?->name_brand ?? '')
                                            .' '.
                                              ($car->car_model?->name_model ?? '')
                                           ),
                    'start_date'          => $reg->start_date,
                    'end_date'            => $reg->end_date,
                    'days'                => $reg->days,
                    'service_price'       => $service?->price_net        ?? 0,
                    'total_value'         => $reg->total_value,
                    'id_parking_register' => $reg->getKey(),
                ];
            });
    
            return response()->json($rows->values());
        }
    
        return view('tenant.admin.parking.index');
    }
    

    public function search(Request $request)
    {
        $plate = $request->query('plate', '');
    
        if (! $plate) {
            return response()->json([
                'found'  => false,
                'message'=> 'No se envió patente',
            ], 200);
        }
    
        $car = Car::where('patent', $plate)
            ->with(['car_belongs.belongs_owner', 'car_brand', 'car_model'])
            ->first();
    
        if (! $car) {
            return response()->json([
                'found'  => false,
                'message'=> 'Patente no encontrada',
            ], 200);
        }
    
        $owner = $car->car_belongs->first()?->belongs_owner;
    
        return response()->json([
            'found'      => true,
            'name'       => trim("{$owner->name} {$owner->last_name}"),
            'phone'      => $owner->number_phone,
            'id_brand'   => $car->car_brand?->id_brand,
            'name_brand' => $car->car_brand?->name_brand,
            'id_model'   => $car->car_model?->id_model,
            'name_model' => $car->car_model?->name_model,
        ], 200);
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
        $parkingServices = Service::where('type_service', 'parking')->get();
    
        return view('tenant.admin.parking.create', compact(
            'brands',
            'models',
            'parkingServices'
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

        $owner = Owner::firstOrCreate(
            ['number_phone' => $data['phone']],
            ['name' => $data['name']],
            ['lastname' => $data['name']],
            ['type_owner' => 'cliente'],
        );

        $car = Car::firstOrCreate(
            ['patent'    => $data['plate']],
            ['id_brand'  => $data['id_brand'],
            'id_model'  => $data['id_model']]
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
            'arrival_km'   => $data['arrival_km'] ?? '11',
            'km_exit'      => $data['km_exit']    ?? '11',
            'days'        => $days,
            'total_value' => $total,
            'id_park' => $park->id
        ]);

        Register::create([
            'id_service' => $data['service_id'],
            'id_parking_register' => $parking->id_parking_register
        ]);
        

        return redirect()->route('estacionamiento.index');

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
        $parking = ParkingRegister::findOrFail($parkingId);
    
        $startDate = Carbon::parse($parking->start_date);
        $endDate   = Carbon::parse($parking->end_date);
    
        $dias_totales = $startDate->diffInDays($endDate);
    
        $tarifa_diaria  = 3000;
        $tarifa_extra   = 20000;
        $valor_total    = ($tarifa_diaria * $dias_totales)
                        + ($tarifa_extra * max(0, $dias_totales - 1));
    
        $cliente = $parking->id_park; 
    
        $pdfContent = Pdf::loadView('pdf.ContractDaily', [
            'cliente'        => $cliente,
            'parking'        => $parking,
            'tarifa_diaria'  => $tarifa_diaria,
            'tarifa_extra'   => $tarifa_extra,
            'dias_totales'   => $dias_totales,
            'valor_total'    => $valor_total,
        ])
        ->setPaper('a4','portrait')
        ->output();
    
        $pdfBase64 = base64_encode($pdfContent);
    
        return response()->view('pdf.print_contrato', compact('pdfBase64'));
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

