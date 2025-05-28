<?php

// app/Http/Controllers/ReservationController.php
namespace App\Http\Controllers;

use App\Models\RentalCar;
use App\Models\Reservation;
use App\Models\BranchOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user','car','branchOffice'])
                                ->orderBy('created_at','desc')
                                ->get();
        return view('tenant.admin.reservations.index', compact('reservations'));
    }

    public function data(Request $request)
    {
        return DataTables::of(
            Reservation::with(['user','car.brand','car.model','branchOffice'])
        )
        ->addColumn('rut', fn($r) => $r->rut)

        ->addColumn('cliente', function($r) {
            $name  = e($r->user->name);
            $email = e($r->user->email);
            return "{$name}<br><small class=\"text-muted\">{$email}</small>";
        })

        ->addColumn('auto', fn($r) => $r->car->brand->name_brand.' '.$r->car->model->name_model)
        ->addColumn('sucursal', fn($r) => $r->branchOffice->name_branch_offices)
        ->addColumn('desde', fn($r) => Carbon::parse($r->start_date)->format('d/m/Y'))
        ->addColumn('hasta', fn($r) => Carbon::parse($r->end_date)->format('d/m/Y'))
        ->addColumn('estado', function($r) {
            return match($r->status) {
                'pending'   => '<span class="border border-warning text-warning px-2 py-1 rounded">Pendiente</span>',
                'confirmed' => '<span class="border border-success text-success px-2 py-1 rounded">Confirmada</span>',
                'cancelled' => '<span class="border border-secondary text-secondary px-2 py-1 rounded">Cancelada</span>',
            };
        })
        ->addColumn('acciones', function($r) {
            if ($r->status === 'pending') {
                $confirm = <<<HTML
                <form action="{$this->url('reservations.confirm',$r)}" method="POST" class="d-inline">
                <input type="hidden" name="_token" value="{$this->csrf()}">
                <button class="btn btn-outline-secondary btn-sm text-dark"><i class="fas fa-check"></i></button>
                </form>
                HTML;
                $cancel = <<<HTML
                <form action="{$this->url('reservations.cancel',$r)}" method="POST" class="d-inline ms-1">
                <input type="hidden" name="_token" value="{$this->csrf()}">
                <button class="btn btn-outline-secondary btn-sm text-dark"><i class="fas fa-times"></i></button>
                </form>
                HTML;
                return $confirm.$cancel;
            }
            return '';
        })

        ->rawColumns(['estado','acciones','cliente'])
        ->toJson();
    }


    protected function csrf()    { return csrf_token(); }
    protected function url($route, $model) {
        return route($route, $model);
    }

    public function confirm(Reservation $reservation)
    {
        $reservation->update(['status'=>'confirmed']);
        return back()->with('success','Reserva confirmada y alquiler generado.');
    }

    public function cancel(Reservation $reservation)
    {
        $reservation->update(['status'=>'cancelled']);
        return back()->with('success','Reserva cancelada.');
    }
}
