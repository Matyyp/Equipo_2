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
}
