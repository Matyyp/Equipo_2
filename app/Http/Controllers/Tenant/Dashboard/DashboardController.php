<?php

namespace App\Http\Controllers\Tenant\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\RegisterRent;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        return view('tenant.admin.sale.analytics');
    }

    public function chartData(Request $request): JsonResponse
    {
        $filter = $request->get('filter', 'daily');
        $user = auth()->user();
        $branchId = $request->get('branch_id');

        // --- Lógica de solo estacionamientos (barras) ---
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

    // NUEVA FUNCIÓN para el gráfico lineal
public function chartLineData(Request $request): JsonResponse
    {
        try {
            $filter = $request->get('filter', 'daily');
            $user = auth()->user();
            $branchId = $request->get('branch_id');

            // --- Parking (Estacionamientos) ---
            $parkingQuery = DB::table('parking_registers as pr')
                ->join('parks as p', 'pr.id_park', '=', 'p.id')
                ->join('services as s', 'p.id_service', '=', 's.id_service')
                ->join('branch_offices as b', 's.id_branch_office', '=', 'b.id_branch')
                ->where('s.type_service', 'parking_daily')
                ->where('pr.status', 'paid');

            // Filtro de sucursal para parking
            if ($user->hasRole('SuperAdmin')) {
                if ($branchId) {
                    $parkingQuery->where('b.id_branch', $branchId);
                }
            } else {
                if (!$user->id_branch_office) {
                    return response()->json([
                        'labels' => [],
                        'parkingValues' => [],
                        'rentValues' => [],
                    ]);
                }
                $parkingQuery->where('b.id_branch', $user->id_branch_office);
                $branchId = $user->id_branch_office; // Para rentas también
            }

            // --- Rentas (pasan por rental_cars para branch) ---
            $rentQuery = DB::table('register_rents as rr')
                ->join('rental_cars as rc', 'rr.rental_car_id', '=', 'rc.id');

            if ($user->hasRole('SuperAdmin')) {
                if ($branchId) {
                    $rentQuery->where('rc.branch_office_id', $branchId);
                }
            } else {
                $rentQuery->where('rc.branch_office_id', $user->id_branch_office);
            }

            if ($filter === 'weekly') {
                $parkingData = $parkingQuery
                    ->selectRaw('YEAR(pr.start_date) as year, WEEK(pr.start_date, 3) as week, SUM(pr.total_value) as total')
                    ->groupByRaw('YEAR(pr.start_date), WEEK(pr.start_date, 3)')
                    ->orderByRaw('YEAR(pr.start_date), WEEK(pr.start_date, 3)')
                    ->get();

                $rentData = $rentQuery
                    ->selectRaw('YEAR(rr.start_date) as year, WEEK(rr.start_date, 3) as week, SUM(rr.payment) as total')
                    ->groupByRaw('YEAR(rr.start_date), WEEK(rr.start_date, 3)')
                    ->orderByRaw('YEAR(rr.start_date), WEEK(rr.start_date, 3)')
                    ->get();

                // Unir semanas
                $labels = [];
                $parkingMap = [];
                $rentMap = [];
                foreach ($parkingData as $row) {
                    $key = $row->year . '-' . $row->week;
                    $labels[$key] = "Semana {$row->week} ({$row->year})";
                    $parkingMap[$key] = $row->total;
                }
                foreach ($rentData as $row) {
                    $key = $row->year . '-' . $row->week;
                    $labels[$key] = "Semana {$row->week} ({$row->year})";
                    $rentMap[$key] = $row->total;
                }
                ksort($labels);

                $parkingValues = [];
                $rentValues = [];
                foreach ($labels as $key => $label) {
                    $parkingValues[] = $parkingMap[$key] ?? 0;
                    $rentValues[] = $rentMap[$key] ?? 0;
                }
                $labels = array_values($labels);

            } else {
                $parkingData = $parkingQuery
                    ->selectRaw('DATE(pr.start_date) as date, SUM(pr.total_value) as total')
                    ->groupByRaw('DATE(pr.start_date)')
                    ->orderBy('date')
                    ->get();

                $rentData = $rentQuery
                    ->selectRaw('DATE(rr.start_date) as date, SUM(rr.payment) as total')
                    ->groupByRaw('DATE(rr.start_date)')
                    ->orderBy('date')
                    ->get();

                // Unir fechas
                $allDates = [];
                $parkingMap = [];
                $rentMap = [];
                foreach ($parkingData as $row) {
                    $allDates[$row->date] = $row->date;
                    $parkingMap[$row->date] = $row->total;
                }
                foreach ($rentData as $row) {
                    $allDates[$row->date] = $row->date;
                    $rentMap[$row->date] = $row->total;
                }
                ksort($allDates);

                $parkingValues = [];
                $rentValues = [];
                foreach ($allDates as $date) {
                    $parkingValues[] = $parkingMap[$date] ?? 0;
                    $rentValues[] = $rentMap[$date] ?? 0;
                }
                $labels = array_values($allDates);
            }

            return response()->json([
                'labels' => $labels,
                'parkingValues' => $parkingValues,
                'rentValues' => $rentValues,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
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
