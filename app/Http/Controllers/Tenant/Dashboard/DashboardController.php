<?php

namespace App\Http\Controllers\Tenant\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('tenant.admin.sale.analytics');
    }

    public function chartData(Request $request): JsonResponse
{
    $filter = $request->get('filter', 'daily'); // default 'daily'
    $user = auth()->user();


    // Base de la consulta
    $query = DB::table('parking_registers as pr')
        ->join('parks as p', 'pr.id_park', '=', 'p.id')
        ->join('services as s', 'p.id_service', '=', 's.id_service')
        ->join('branch_offices as b','s.id_branch_office','=','b.id_branch')
        ->where('b.id_branch',$user->id_branch_office)
        ->where('s.type_service', 'parking_daily')
        ->where('pr.status','paid');


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

    // Estacionamientos activos
    $activeCount = DB::table('parks as p')
        ->join('services as s', 'p.id_service', '=', 's.id_service')
        ->where('s.type_service', 'parking_daily')
        ->where('p.status', 'parked')
        ->count();

    $totalSpots = 50; // Puedes hacer esto dinámico si lo deseas
    $availableCount = $totalSpots - $activeCount;

    return response()->json([
        'labels' => $labels,
        'values' => $values,
        'parking' => [
            'ocupados' => $activeCount,
            'disponibles' => $availableCount,
        ]
    ]);
}


}
