<?php

namespace App\Http\Controllers;

use App\Models\RegisterRent;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ExternalUser;
use App\Models\RentalCar;
use App\Models\Reservation;
use Carbon\Carbon;

class RegisterRentController extends Controller
{
    public function index()
    {
        return view('tenant.admin.register_rents.index');
    }

    public function data()
    {
        $query = RegisterRent::with(['rentalCar.brand', 'rentalCar.model', 'rentalCar.branchOffice']);

        return datatables()->eloquent($query)
            ->addColumn('auto', function ($r) {
                $brand = optional($r->rentalCar->brand)->name_brand ?? 'N/A';
                $model = optional($r->rentalCar->model)->name_model ?? '';
                return trim("{$brand} {$model}");
            })
            ->addColumn('sucursal', fn($r) => optional($r->rentalCar->branchOffice)->name_branch_offices ?? 'N/A')
            ->addColumn('acciones', function ($r) {
                $accidentBtn = '';
                if ($r->rentalCar) {
                    // Ahora pasamos id_rent (el id del registro de arriendo)
                    $accidentUrl = route('accidente.create', ['id_rent' => $r->id]);
                    $accidentBtn = '<a href="' . $accidentUrl . '" class="btn btn-outline-info btn-sm text-info" title="Registrar Accidente">
                        <i class="fas fa-car-crash"></i>
                    </a>';
                }
                // Separación mínima entre botones (solo me-1 en el primero)
                return $showBtn . $accidentBtn;
                $verBtn = '<a href="' . route('registro-renta.show', $r->id) . '" class="btn btn-outline-info btn-sm text-info me-1" title="Ver">
                    <i class="fas fa-eye"></i>
                </a>';

                $reseñaBtn = $r->userRatings->isEmpty()
                    ? '<button class="btn btn-outline-info btn-sm text-info ml-1" data-id="' . $r->id . '" data-toggle="modal" data-target="#ratingModal" title="Añadir Reseña">
                        <i class="fas fa-star"></i>
                    </button>' : '';

                $completarBtn = $r->status === 'en_progreso'
                    ? '<button class="btn btn-outline-info btn-sm text-info ml-1 completar-btn" data-id="' . $r->id . '" data-toggle="modal" data-target="#completarModal">
                        <i class="fas fa-check-circle"></i>
                    </button>' : '';

                return $verBtn . $reseñaBtn . $completarBtn;
            })
            ->addColumn('status', fn($r) => $r->status === 'completado' 
                ? '<span class="border border-success text-success px-2 py-1 rounded">Completado</span>' 
                : '<span class="border border-warning text-warning px-2 py-1 rounded">En Progreso</span>')
            ->rawColumns(['acciones', 'status'])
            ->toJson();
    }

    public function show($id)
    {
        $register = RegisterRent::with(['rentalCar.brand', 'rentalCar.branchOffice'])->findOrFail($id);
        return view('tenant.admin.register_rents.show', compact('register'));
    }

    
    public function create()
    {
        $cars = RentalCar::with('brand', 'model')->get();
        return view('tenant.admin.register_rents.create', compact('cars'));
    }

    public function fechasOcupadas($id)
    {
        $fechasOcupadas = [];

        // Fechas de reservas activas
        $reservas = Reservation::where('car_id', $id)
            ->where('status', '!=', 'cancelled')
            ->get(['start_date', 'end_date']);

        foreach ($reservas as $reserva) {
            $inicio = \Carbon\Carbon::parse($reserva->start_date);
            $fin = \Carbon\Carbon::parse($reserva->end_date);
            while ($inicio <= $fin) {
                $fechasOcupadas[] = $inicio->format('Y-m-d');
                $inicio->addDay();
            }
        }

        // Fechas de registros manuales
        $rents = RegisterRent::where('rental_car_id', $id)
            ->get(['start_date', 'end_date']);

        foreach ($rents as $rent) {
            $inicio = \Carbon\Carbon::parse($rent->start_date);
            $fin = \Carbon\Carbon::parse($rent->end_date);
            while ($inicio <= $fin) {
                $fechasOcupadas[] = $inicio->format('Y-m-d');
                $inicio->addDay();
            }
        }

        // Eliminar duplicados y retornar
        return response()->json(array_values(array_unique($fechasOcupadas)));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_car' => 'required|exists:rental_cars,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number_phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:255',
            'driving_license' => 'required|string',
            'class_licence' => 'required|string',
            'expire' => 'required|date',
            'guarantee' => 'required|numeric',
            'departure_fuel' => 'required|string',
            'km_exit' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Validar solapamiento con reservas o arriendos
        $existsReservation = Reservation::where('car_id', $request->id_car)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })->exists();

        $existsRent = RegisterRent::where('rental_car_id', $request->id_car)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })->exists();

        if ($existsReservation || $existsRent) {
            return back()->withErrors(['id_car' => 'Este auto ya está reservado o arrendado en las fechas seleccionadas.'])->withInput();
        }

        $email = $request->email;
        $name = $request->name;
        $externalUserId = null;

        // Si el correo existe en 'users', usar su nombre
        $user = \App\Models\User::where('email', $email)->first();
        if ($user) {
            $name = $user->name;
        }

        // Si el correo existe en 'external_users', obtenerlo
        $externalUser = ExternalUser::where('email', $email)->first();
        if ($externalUser) {
            $externalUserId = $externalUser->id;
        } else if (!$user) {
            // Solo crear si no está en users ni en external_users
            $externalUser = ExternalUser::create([
                'name' => $name,
                'email' => $email,
            ]);
            $externalUserId = $externalUser->id;
        }

        $start = Carbon::parse($request['start_date']);
        $end = Carbon::parse($request['end_date']);
        $days = $start->diffInDays($end) + 1;

        $car = RentalCar::findOrFail($request->id_car);
        $amount = $car->price_per_day * $days;

        RegisterRent::create([
            'client_rut' => $request->rut,
            'client_name' => $name,
            'client_email' => $email,
            'number_phone' => $request->number_phone,
            'rental_car_id' => $request->id_car,
            'id_external_user' => $externalUserId,
            'address' => $request->address,
            'driving_license' => $request->driving_license,
            'class_licence' => $request->class_licence,
            'expire' => $request->expire,
            'guarantee' => $request->guarantee,
            'payment'  => $amount,
            'departure_fuel' => $request->departure_fuel,
            'km_exit' => $request->km_exit,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'observation' => $request->observation,
        ]);

        return redirect()->route('registro-renta.index')->with('success', 'Registro de arriendo guardado correctamente.');
    }
    public function buscarClientePorCorreo(Request $request)
    {
        $email = $request->query('email');

        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            $user = \App\Models\ExternalUser::where('email', $email)->first();
        }

        if ($user) {
            return response()->json([
                'name' => $user->name,
                'found' => true
            ]);
        }

        return response()->json(['found' => false]);
    }

    public function completar(Request $request, $id)
    {
        $request->validate([
            'km_llegada' => 'required|integer|min:0',
        ]);

        $renta = RegisterRent::findOrFail($id);
        $renta->status = 'completado';
        $renta->save();

        // Actualizar kilometraje del auto
        $car = $renta->rentalCar;
        if ($car) {
            $car->km = $request->km_llegada;
            $car->save();
        }

        return redirect()->route('registro-renta.index')->with('success', 'Arriendo completado y kilometraje actualizado.');
    }

}
