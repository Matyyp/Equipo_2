<?php

namespace App\Http\Controllers\Tenant\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\MaintenanceAlertService;
use App\Models\RegisterRent;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        return view('tenant.admin.sale.analytics');
    }
public function mantencionesAlert(MaintenanceAlertService $service)
{
    $alertData = $service->getUpcoming();

    return view('tenant.admin.dashboard', [
        'mantencionesProximas' => $alertData['entries'],
        'mantencionesTotal' => $alertData['total'],
    ]);
}

public function chartData(Request $request): JsonResponse
{
    $filter = $request->get('filter', 'daily');
    $user = auth()->user();
    $branchId = $request->get('branch_id');

    $query = DB::table('parking_registers as pr')
        ->join('parks as p', 'pr.id_park', '=', 'p.id')
        ->join('services as s', 'p.id_service', '=', 's.id_service')
        ->join('branch_offices as b', 's.id_branch_office', '=', 'b.id_branch')
        ->where('s.type_service', 'parking_daily')
        ->where('pr.status', 'paid');

    if ($user->hasRole('SuperAdmin')) {
        if ($branchId) {
            $query->where('b.id_branch', $branchId);
        }
    } else {
        if (!$user->id_branch_office) {
            return response()->json([
                'labels' => [],
                'values' => [],
                'parking' => [
                    'ocupados' => 0,
                    'disponibles' => 0,
                ]
            ]);
        }
        $query->where('b.id_branch', $user->id_branch_office);
    }

    if ($filter === 'weekly') {
        $barData = $query
            ->selectRaw('YEAR(pr.start_date) as year, WEEK(pr.start_date, 3) as week, SUM(pr.total_value) as total')
            ->groupByRaw('YEAR(pr.start_date), WEEK(pr.start_date, 3)')
            ->orderByRaw('YEAR(pr.start_date), WEEK(pr.start_date, 3)')
            ->get();

        $labels = $barData->map(fn($row) => "Semana {$row->week} ({$row->year})");
        $values = $barData->pluck('total');
    } else {
        $barData = $query
            ->selectRaw('DATE(pr.start_date) as date, SUM(pr.total_value) as total')
            ->groupByRaw('DATE(pr.start_date)')
            ->orderBy('date')
            ->get();

        $labels = $barData->pluck('date');
        $values = $barData->pluck('total');
    }

    // --- Estacionamientos activos
    $activeQuery = DB::table('parks as p')
        ->join('services as s', 'p.id_service', '=', 's.id_service')
        ->where('s.type_service', 'parking_daily')
        ->where('p.status', 'parked');

    if ($user->hasRole('SuperAdmin')) {
        if ($branchId) {
            $activeQuery->where('s.id_branch_office', $branchId);
        }
    } else {
        $activeQuery->where('s.id_branch_office', $user->id_branch_office);
    }

    $activeCount = $activeQuery->count();

    
    $totalSpots = 50;
    $availableCount = max(0, $totalSpots - $activeCount);

    return response()->json([
        'labels' => $labels,
        'values' => $values,
        'parking' => [
            'ocupados' => $activeCount,
            'disponibles' => $availableCount,
        ]
    ]);
}
public function getRentsDataDashboard()
{
    $query = RegisterRent::with(['rentalCar.brand', 'rentalCar.model', 'rentalCar.branchOffice'])
        ->where('status', 'en_progreso'); // solo mostrar en progreso en el dashboard

    return datatables()->eloquent($query)
        ->addColumn('auto', function ($r) {
            $brand = optional($r->rentalCar->brand)->name_brand ?? 'N/A';
            $model = optional($r->rentalCar->model)->name_model ?? '';
            return trim("{$brand} {$model}");
        })
        ->addColumn('sucursal', fn($r) => optional($r->rentalCar->branchOffice)->name_branch_offices ?? 'N/A')
        ->addColumn('acciones', function ($r) {
            $verBtn = '<a href="' . route('registro-renta.show', $r->id) . '" class="btn btn-outline-info btn-sm text-info me-1" title="Ver">
                <i class="fas fa-eye"></i>
            </a>';
            return $verBtn;
        })
        ->addColumn('status', fn($r) => $r->status === 'completado' 
            ? '<span class="border border-success text-success px-2 py-1 rounded">Completado</span>' 
            : '<span class="border border-warning text-warning px-2 py-1 rounded">En Progreso</span>')
        ->rawColumns(['acciones', 'status'])
        ->toJson();
}

}
