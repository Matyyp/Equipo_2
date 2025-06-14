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
                $showBtn = '<a href="' . route('registro-renta.show', $r->id) . '" class="btn btn-outline-info btn-sm text-info mr-1" title="Ver">
                    <i class="fas fa-eye"></i>
                </a>';
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