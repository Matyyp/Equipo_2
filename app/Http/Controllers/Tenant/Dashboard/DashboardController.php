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

    // Obtener número de estacionamientos dinámicamente desde la sucursal
    if ($user->hasRole('SuperAdmin')) {
        if ($branchId) {
            $totalSpots = \DB::table('branch_offices')
                ->where('id_branch', $branchId)
                ->value('number_parkings') ?? 0;
        } else {
            $totalSpots = \DB::table('branch_offices')->sum('number_parkings');
        }
    } else {
        $totalSpots = \DB::table('branch_offices')
            ->where('id_branch', $user->id_branch_office)
            ->value('number_parkings') ?? 0;
    }

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
    public function carTypeRanking(Request $request): JsonResponse
    {
        $user = auth()->user();
        $branchId = $request->get('branch_id');

        // Query para ranking: cuenta de rentas agrupadas por tipo de auto (modelo y/o marca)
        $carTypeQuery = \DB::table('register_rents as rr')
            ->join('rental_cars as rc', 'rr.rental_car_id', '=', 'rc.id')
            ->join('brands as b', 'rc.brand_id', '=', 'b.id_brand')
            ->join('model_cars as m', 'rc.model_car_id', '=', 'm.id_model')
            ->selectRaw("CONCAT(b.name_brand, ' ', m.name_model) as car_type, COUNT(*) as total")
            ->groupBy('b.name_brand', 'm.name_model')
            ->orderByDesc('total');

        // Si hay filtro de sucursal
        if ($user->hasRole('SuperAdmin')) {
            if ($branchId) {
                $carTypeQuery->where('rc.branch_office_id', $branchId);
            }
        } else {
            $carTypeQuery->where('rc.branch_office_id', $user->id_branch_office);
        }

        $ranking = $carTypeQuery->limit(10)->get();

        return response()->json([
            'labels' => $ranking->pluck('car_type'),
            'values' => $ranking->pluck('total'),
        ]);
    }
public function topUsersRanking(Request $request): JsonResponse
{
    $user = auth()->user();
    $branchId = $request->get('branch_id');

    $query = \DB::table('register_rents as rr')
        ->selectRaw('rr.client_name, rr.client_email, COUNT(*) as total, rr.client_rut')
        ->groupBy('rr.client_name', 'rr.client_email', 'rr.client_rut')
        ->orderByDesc('total');

    // Filtro de sucursal si aplica (si tienes branch_id en rental_cars, puedes hacer join como en otros)
    if ($user->hasRole('SuperAdmin')) {
        if ($branchId) {
            $query->join('rental_cars as rc', 'rr.rental_car_id', '=', 'rc.id');
            $query->where('rc.branch_office_id', $branchId);
        }
    } else {
        $query->join('rental_cars as rc', 'rr.rental_car_id', '=', 'rc.id');
        $query->where('rc.branch_office_id', $user->id_branch_office);
    }

    $ranking = $query->limit(10)->get();

    return response()->json([
        'users' => $ranking
    ]);
}
public function userRatings(Request $request, $client_rut)
{
    $ratings = \App\Models\UserRating::whereHas('rent', function($q) use ($client_rut) {
        $q->where('client_rut', $client_rut);
    })->orderByDesc('id')->get();

    return response()->json([
        'ratings' => $ratings
    ]);
}
}

