<?php

namespace App\Services;

use App\Models\Maintenance;
use Carbon\Carbon;

class MaintenanceAlertService
{
    public function getUpcoming()
    {
        $soon = now()->addDays(7);

        // Obtener todas las mantenciones próximas
        $allUpcoming = Maintenance::with(['car.brand', 'car.model', 'type'])
            ->join('rental_cars', 'rental_cars.id', '=', 'maintenances.rental_car_id')
            ->where('maintenances.is_completed', false)
            ->where(function ($query) use ($soon) {
                $query->whereNotNull('maintenances.scheduled_date')
                      ->whereBetween('maintenances.scheduled_date', [now(), $soon])
                      ->orWhere(function ($q) {
                          $q->whereNotNull('maintenances.scheduled_km')
                            ->whereRaw('rental_cars.km >= maintenances.scheduled_km - 100');
                      });
            })
            ->select('maintenances.*')
            ->get();

        // Total de mantenciones próximas
        $totalCount = $allUpcoming->count();

        // Agrupado por vehículo (una mantención por vehículo)
        $grouped = $allUpcoming
            ->groupBy('rental_car_id')
            ->map(function ($group) {
                return $group->sortBy(function ($m) {
                    return $m->scheduled_date ?? now()->addDays(30); // prioriza la fecha
                })->first();
            })
            ->values();

        return [
            'total' => $totalCount,
            'entries' => $grouped
        ];
    }
}
