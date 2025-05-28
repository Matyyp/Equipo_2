<?php

namespace App\Http\Controllers;

use App\Models\RegisterRent;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
                return '<a href="' . route('registro-renta.show', $r->id) . '" class="btn btn-outline-secondary btn-sm text-dark me-1" title="Ver">
                    <i class="fas fa-eye"></i>
                </a>';
            })
            ->rawColumns(['acciones'])
            ->toJson();
    }

    public function show($id)
    {
        $register = RegisterRent::with(['rentalCar.brand', 'rentalCar.branchOffice'])->findOrFail($id);
        return view('tenant.admin.register_rents.show', compact('register'));
    }

}
