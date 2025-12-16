<?php

namespace App\Http\Controllers;

use App\Models\RentalCar;
use App\Models\Reservation;
use App\Models\User;
use App\Models\ReservationPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Transbank\Webpay\WebpayPlus;
use Carbon\Carbon;
use App\Mail\ReservationConfirmed;
use App\Mail\ReservationSolicitud;
use Illuminate\Support\Facades\Mail;

class TransbankController extends Controller
{
    public function init(Request $request, RentalCar $car)
    {
        $data = $request->validate([
            'rut'              => 'required|string|max:20',
            'branch_office_id' => 'required|exists:branch_offices,id_branch',
            'start_date'       => 'required|date|after_or_equal:today',
            'phone'            => 'required|regex:/^[0-9]{9}$/',
            'end_date'         => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.after_or_equal' => 'La fecha de inicio debe ser hoy o una fecha futura.',
            'end_date.after_or_equal'   => 'La fecha de término debe ser igual o posterior a la fecha de inicio.',
        ]);

        $admin = User::where("name", "administrador")
             ->where("email", "!=", "admin@admin.com")
             ->first();
        
        $conflict = Reservation::where('car_id', $car->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                  ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('start_date', '<', $data['start_date'])
                         ->where('end_date', '>', $data['end_date']);
                  });
            })->exists();

        if ($conflict) {
            return back()
                ->withErrors(['start_date' => 'El auto no está disponible en esas fechas.'])
                ->withInput();
        }

        // Guardar datos de la reserva temporalmente en sesión
        session([
            'reservation_data' => [
                'user_id'          => Auth::id(),
                'car_id'           => $car->id,
                'rut'              => $data['rut'],
                'branch_office_id' => $data['branch_office_id'],
                'start_date'       => $data['start_date'],
                'end_date'         => $data['end_date'],
                'phone'            => $data['phone']
            ]
        ]);

        //aqui modificado esto es de la funcion confirm
        $data = session('reservation_data');

        $reservation = Reservation::create([
                'user_id'          => $data['user_id'],
                'car_id'           => $data['car_id'],
                'rut'              => $data['rut'],
                'branch_office_id' => $data['branch_office_id'],
                'start_date'       => $data['start_date'],
                'end_date'         => $data['end_date'],
                'status'           => 'pending',
                'phone'            => $data['phone']
            ]);
        session()->forget(['reservation_data', 'webpay_token']);

        $reservation->load(['car.brand', 'car.model', 'branchOffice.business']);
        $businessName = optional($reservation->branchOffice->business)->name_business ?? 'Nombre no disponible';

        $user = Auth::user();
        if ($user && $user->email) {
            Mail::to($user->email)->send(new ReservationConfirmed($reservation, $businessName));
            Mail::to($admin)->send(new ReservationSolicitud($reservation, $businessName));
        }

        return view('webpay.success', compact('reservation'));

        //Hasta aqui

        // $start = Carbon::parse($data['start_date']);
        // $end = Carbon::parse($data['end_date']);
        // $days = $start->diffInDays($end) + 1; 

        // $amount = $car->price_per_day * $days;
        // $buyOrder = uniqid('ORD-');
        // $sessionId = uniqid('SES-');
        // session(['webpay_session_id' => $sessionId]);
        // $returnUrl = route('webpay.confirm');

        // $response = WebpayPlus::transaction()->create($buyOrder, $sessionId, $amount, $returnUrl);

        // session(['webpay_token' => $response->getToken()]);

        // return redirect($response->getUrl() . '?token_ws=' . $response->getToken());
    }

    public function confirm(Request $request)
    {
        $token = $request->input('token_ws');

        // if (!$token) {
        //     return redirect()->route('landings.available')->with('error', 'Token de pago inválido.');
        // }

        // $response = WebpayPlus::transaction()->commit($token);

        // if ($response->isApproved()) {
        //     $data = session('reservation_data');

        //     if (!$data) {
        //         return redirect()->route('landings.available')->with('error', 'No se encontraron datos de reserva.');
        //     }

        //     // Crear la reserva
        //     $reservation = Reservation::create([
        //         'user_id'          => $data['user_id'],
        //         'car_id'           => $data['car_id'],
        //         'rut'              => $data['rut'],
        //         'branch_office_id' => $data['branch_office_id'],
        //         'start_date'       => $data['start_date'],
        //         'end_date'         => $data['end_date'],
        //         'status'           => 'pending',
        //     ]);

        //     // Registrar el pago
        //     $payment = ReservationPayment::create([
        //         'reservation_id'      => $reservation->id,
        //         'token'               => $token,
        //         'session_id' => session('webpay_session_id'), 
        //         'amount'              => $response->getAmount(),
        //         'authorization_code'  => $response->getAuthorizationCode(),
        //         'payment_type'        => $response->getPaymentTypeCode(),
        //         'response_code'       => $response->getResponseCode(),
        //         'buy_order' => $response->getBuyOrder(),
        //     ]);

        //     /// Limpiar sesión
        //     session()->forget(['reservation_data', 'webpay_token']);

        //     $reservation->load(['car.brand', 'car.model', 'branchOffice.business']);
        //     $businessName = optional($reservation->branchOffice->business)->name_business ?? 'Nombre no disponible';

        //     // Envio de correo
        //     $user = Auth::user();
        //     if ($user && $user->email) {
        //         Mail::to($user->email)->send(new ReservationConfirmed($reservation, $payment, $businessName));
        //     }

        //     return view('webpay.success', compact('reservation', 'payment'));
        // }

        // return view('webpay.failure');
    }
}
