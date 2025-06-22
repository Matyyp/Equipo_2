<?php

namespace App\Http\Controllers;

use App\Models\RegisterRent;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ExternalUser;
use App\Models\RentalCar;
use App\Models\Reservation;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Business;
use App\Models\ContactInformation;

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
                $verBtn = '<a href="' . route('registro-renta.show', $r->id) . '" class="btn btn-outline-info btn-sm text-info" title="Ver">
                    <i class="fas fa-eye"></i>
                </a>';

                $contratoBtn = '<a href="' . route('register_rents.contrato_pdf', $r->id) . '" class="btn btn-outline-info btn-sm text-info ml-1" title="Generar Contrato" target="_blank">
                    <i class="fas fa-file-pdf"></i>
                </a>';

                $reseñaBtn = $r->userRatings->isEmpty()
                    ? '<button class="btn btn-outline-info btn-sm text-info ml-1" data-id="' . $r->id . '" data-toggle="modal" data-target="#ratingModal" title="Añadir Reseña">
                        <i class="fas fa-star"></i>
                    </button>' : '';

                $completarBtn = $r->status === 'en_progreso'
                    ? '<button class="btn btn-outline-info btn-sm text-info ml-1 completar-btn" data-id="' . $r->id . '" data-toggle="modal" data-target="#completarModal">
                        <i class="fas fa-check-circle"></i>
                    </button>' : '';

                return $verBtn . $contratoBtn . $reseñaBtn . $completarBtn;
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
        ], [
            'id_car.required' => 'El campo auto es obligatorio.',
            'id_car.exists' => 'El auto seleccionado no es válido.',
            'name.required' => 'El campo nombre es obligatorio.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'address.required' => 'El campo dirección es obligatorio.',
            'driving_license.required' => 'El campo licencia de conducir es obligatorio.',
            'class_licence.required' => 'El campo clase de licencia es obligatorio.',
            'expire.required' => 'El campo fecha de expiración es obligatorio.',
            'guarantee.required' => 'El campo garantía es obligatorio.',
            'guarantee.numeric' => 'La garantía debe ser un número.',
            'departure_fuel.required' => 'Debe seleccionar el nivel de combustible de salida.',
            'km_exit.required' => 'El campo kilómetros de salida es obligatorio.',
            'km_exit.numeric' => 'Los kilómetros deben ser un número.',
            'start_date.required' => 'El campo fecha de inicio es obligatorio.',
            'end_date.required' => 'El campo fecha de término es obligatorio.',
            'end_date.after_or_equal' => 'La fecha de término debe ser igual o posterior a la de inicio.',
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

    public function contratoPDF($id)
    {
        $rent = RegisterRent::with(['rentalCar'])->findOrFail($id);

        $car = $rent->rentalCar;

        // Logo base64 desde storage
        $logo = Business::value('logo');
        $logoPath = storage_path('app/public/' . $logo);
        $logoBase64 = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));

        // Datos de contacto (si tienes relacionados, por ahora mock)
        $datosContacto = ContactInformation::limit(3)->get(); // cambia esto si tienes relación

        $traduccionesContacto = [
            'email'    => 'Correo Electrónico',
            'phone'    => 'Teléfono',
            'mobile'   => 'Celular',
            'whatsapp' => 'WhatsApp',
            'website'  => 'Sitio Web',
            'social'   => 'Red Social',
        ];

        $datosContactoTraducido = $datosContacto->map(function ($contacto) use ($traduccionesContacto) {
            return [
                'tipo' => $traduccionesContacto[$contacto->type_contact] ?? ucfirst($contacto->type_contact),
                'dato' => $contacto->data_contact,
            ];
        });


        // Datos para la vista PDF
        $data = [
            'url_logo'      => $logoBase64,
            'marca'  => $car->brand->name_brand ?? '',
            'modelo' => $car->model->name_model ?? '',
            'patente'       => $car->patent ?? 'N/A',
            'inicio'        => Carbon::parse($rent->start_date)->format('d-m-Y'),
            'termino'       => Carbon::parse($rent->end_date)->format('d-m-Y'),
            'km_exit'       => $rent->km_exit ?? '',
            'combustible'   => $rent->departure_fuel ?? '',
            'garantia'      => $rent->guarantee ?? 0,
            'pago'          => $rent->payment ?? 0,
            'nombre'        => $rent->client_name ?? '',
            'rut'           => $rent->client_rut ?? '',
            'email'         => $rent->client_email ?? '',
            'telefono'      => $rent->number_phone ?? '',
            'licencia'      => $rent->driving_license ?? '',
            'clase'         => $rent->class_licence ?? '',
            'vence'         => Carbon::parse($rent->expire)->format('d-m-Y'),
            'direccion'     => $rent->address ?? '',
            'observacion'   => $rent->observation ?? '',
            'direccion_sucursal' => $car->branch_office->street ?? 'Sucursal no asignada', // ajusta si tienes relación
            'horario'       => $car->branch_office->schedule ?? 'Horario no disponible',   // idem
            'datos_contacto'=> $datosContactoTraducido,
        ];

        $pdf = PDF::loadView('tenant.admin.register_rents.contract_pdf', $data)
            ->setPaper('a4', 'portrait');

        $pdfBase64 = base64_encode($pdf->output());

        return view('pdf.print_contrato', compact('pdfBase64'));
    }


}
