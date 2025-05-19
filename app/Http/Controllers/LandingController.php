<?php

namespace App\Http\Controllers;

use App\Models\RentalCar;
use App\Models\Reservation;
use App\Models\BranchOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function availableCars(Request $request)
    {
        $query = RentalCar::with(['brand','model','images'])
                          ->where('is_active', true);

        if ($search = $request->input('search')) {
            $query->where(function($q) use($search) {
                $q->whereHas('brand', fn($q) => 
                    $q->where('name_brand','like',"%{$search}%")
                )->orWhereHas('model', fn($q) => 
                    $q->where('name_model','like',"%{$search}%")
                );
            });
        }

        $cars = $query->get();

        return view('tenant.landings.available_cars', compact('cars'));
    }

    public function availableCarsPartial(Request $request)
    {
        $query = RentalCar::with(['brand','model','images'])
                        ->where('is_active', true);

        if ($search = $request->input('search')) {
            $query->where(function($q) use($search) {
                $q->whereHas('brand', fn($q)=>
                        $q->where('name_brand','like', "%{$search}%")
                    )
                ->orWhereHas('model', fn($q)=>
                        $q->where('name_model','like', "%{$search}%")
                    );
            });
        }

        $cars = $query->get();

        // Renderizamos sólo el partial que contiene el grid de tarjetas
        return view('tenant.landings._cars', compact('cars'))->render();
    }

    public function reserve(RentalCar $car)
    {
        $branches = BranchOffice::pluck('name_branch_offices','id_branch');
        $selectedBranchId = $car->branch_office_id
        ?? $branches->keys()->first();
        return view('tenant.landings.reserve', compact('car','branches','selectedBranchId'));
    }

    /**
     * Almacenar la reserva web.
     */
    public function storeReservation(Request $request, RentalCar $car)
    {
        $data = $request->validate([
            'rut'              => 'required|string|max:20',
            'branch_office_id' => 'required|exists:branch_offices,id_branch',
            'start_date'       => 'required|date|after_or_equal:today',
            'end_date'         => 'required|date|after_or_equal:start_date',
        ]);

        // chequeo de solapamiento
        $conflict = Reservation::where('car_id',$car->id)
            ->whereIn('status',['pending','confirmed'])
            ->where(function($q) use($data) {
                $q->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                ->orWhereBetween('end_date',   [$data['start_date'], $data['end_date']])
                ->orWhere(function($q2) use($data) {
                    $q2->where('start_date','<',$data['start_date'])
                        ->where('end_date','>',$data['end_date']);
                });
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['start_date' => 'El auto no está disponible en esas fechas.'])
                ->withInput();
        }

        Reservation::create([
            'user_id'          => Auth::id(),
            'rut'              => $data['rut'],
            'car_id'           => $car->id,
            'branch_office_id' => $data['branch_office_id'],
            'start_date'       => $data['start_date'],
            'end_date'         => $data['end_date'],
        ]);

        return redirect()
            ->route('landings.available')
            ->with('success','Tu reserva ha quedado en estado *pendiente*, pronto te confirmaremos.');
    }
}
