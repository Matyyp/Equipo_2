<?php

namespace App\Http\Controllers\Tenant\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index()
    {
        return view('tenant.admin.dashboard');
    }

    public function chartData(): JsonResponse
{
    $barData = DB::table('parking_registers')
        ->selectRaw('DATE(start_date) as date, SUM(total_value) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Total de estacionamientos disponibles
    $totalSpots = 50;

$activeCount = DB::table('parking_registers')
    ->whereDate('end_date', '>', now())
    ->count();

$availableCount = $totalSpots - $activeCount;

return response()->json([
    'labels' => $barData->pluck('date'),
    'values' => $barData->pluck('total'),
    'parking' => [
        'ocupados' => $activeCount,
        'disponibles' => $availableCount,
    ]
]);

}
}
