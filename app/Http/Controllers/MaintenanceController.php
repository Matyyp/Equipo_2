<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\MaintenanceType;
use App\Models\RentalCar;
use App\Models\MaintenanceImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaintenanceController extends Controller
{
        public function index()
        {
            return view('tenant.admin.maintenance.entries.index');
        }

        public function data(Request $request)
        {
            return DataTables::of(Maintenance::with(['car', 'type', 'images']))
                ->addColumn('car', function ($m) {
                    $car = $m->car;
                    $model = $car->model; // BrandModel
                    $brand = $car->brand; // Brand

                    return '' . ($brand->name_brand ?? '-') . ' ' . ($model->name_model ?? '-');
                })
                ->addColumn('type', fn($m) => e($m->type->name ?? ''))

                ->addColumn('status', function ($m) {
                // 1. Verificar si el auto está actualmente en mantención
                $inMaintenance = $m->car && !$m->car->is_active;
                
                // 2. Verificar si la tarea está completada
                $isCompleted = $m->is_completed;

                // 3. Lógica para determinar qué mostrar
                if ($inMaintenance) {
                    if ($isCompleted) {
                        // Auto en mantención PERO esta tarea específica está completada
                        return "<span class='border border-success text-success px-2 py-1 rounded font-weight-bold'>Completada</span>";
                    } else {
                        // Auto en mantención y tarea pendiente
                        return "<span class='border border-danger text-danger px-2 py-1 rounded font-weight-bold'>En mantención</span>";
                    }
                } else {
                    // Auto NO está en mantención - mostrar estado normal de la tarea
                    if ($isCompleted) {
                        return "<span class='border border-success text-success px-2 py-1 rounded'>Completada</span>";
                    } elseif ($m->employee_name) {
                        return "<span class='border border-warning text-warning px-2 py-1 rounded'>En proceso</span>";
                    } else {
                        return "<span class='border border-secondary text-secondary px-2 py-1 rounded'>Programada</span>";
                    }
                }
            })
            ->addColumn('proximidad', function ($m) {
                $fechaTexto = null;
                $kmTexto = null;

                // Fecha programada
                if (!empty($m->scheduled_date)) {
                    $fechaTexto = '' . \Carbon\Carbon::parse($m->scheduled_date)->format('d-m-Y');
                }

                // Asegúrate de que la relación esté cargada y tenga valor
                if (!is_null($m->scheduled_km) && $m->car && !is_null($m->car->km)) {
                    $kmFaltantes = $m->scheduled_km - $m->car->km;
                    $kmTexto = $kmFaltantes <= 0
                        ? '⚠ Excedido'
                        : "{$kmFaltantes} km para Mantención";
                }

                if ($fechaTexto && $kmTexto) {
                    return "{$fechaTexto} / {$kmTexto}";
                }

                return $fechaTexto ?? $kmTexto ?? '-';
            })



                ->addColumn('acciones', function ($m) {
                    $edit = route('maintenance.entries.edit', ['maintenance' => $m]);
                    $delete = route('maintenance.entries.destroy', ['maintenance' => $m]);
                    $mark = route('maintenance.entries.mark-unavailable', ['entry' => $m]); // 👈 Esta usa 'entry' aún porque no está dentro del resource
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    $carInactive = $m->car && !$m->car->is_active;
                    $completed = $m->is_completed;

                    // Botón de marcar como en mantención
                    $disableMaintenance = $completed || $carInactive;
                    $maintenanceBtn = $disableMaintenance
                        ? '<button class="btn btn-sm btn-outline-info" disabled><i class="fas fa-tools"></i></button>'
                        : <<<HTML
                            <form action="{$mark}" method="POST" class="form-mark d-inline">
                                {$csrf}
                                <button class="btn btn-sm btn-outline-info"><i class="fas fa-tools"></i></button>
                            </form>
                        HTML;

                    // Botón de marcar como completado
                    $completeBtn = $completed
                        ? '<button class="btn btn-sm btn-outline-info" disabled><i class="fas fa-check"></i></button>'
                        : <<<HTML
                            <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#completeMaintenanceModal_{$m->id}">
                                <i class="fas fa-check"></i>
                            </button>
                        HTML;

                    // Botón para ver detalles (modal)
                    $viewBtn = <<<HTML
                        <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#viewMaintenanceModal_{$m->id}">
                            <i class="fas fa-eye"></i>
                        </button>
                    HTML;

                    return <<<HTML
                        <a href="{$edit}" class="btn btn-sm btn-outline-info"><i class="fas fa-pen"></i></a>

                        {$maintenanceBtn}
                        {$completeBtn}
                        {$viewBtn}
                        <form action="{$delete}" method="POST" class="form-delete d-inline">
                            {$csrf}{$method}
                            <button class="btn btn-sm btn-outline-info"><i class="fas fa-trash"></i></button>
                        </form>
                    HTML;
                })


                ->rawColumns(['status', 'acciones'])
                ->make(true);
        }


        public function markUnavailable(Maintenance $entry)
        {
            $car = $entry->car;

            if ($car && $car->is_active) {
                $car->is_active = 0;
                $car->save();

                return redirect()->route('maintenance.entries.index')
                    ->with('success', 'Vehículo marcado como "en mantenimiento" y desactivado para arriendo.');
            }

            return redirect()->route('maintenance.entries.index')
                ->with('warning', 'Este vehículo ya está desactivado.');
        }

        public function markCompleted(Request $request, Maintenance $entry)
        {
            $data = $request->validate([
                'employee_name'   => 'required|string|max:255',
                'completed_date'  => 'required|date',
                'location'        => 'nullable|string|max:255',
                'invoice_number'  => 'nullable|string|max:255',
                'invoice_file'    => 'nullable|image|max:2048',
            ]);

            // Guardar imagen si hay
            if ($request->hasFile('invoice_file')) {
                foreach ($entry->images as $img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }

                $path = $request->file('invoice_file')->store('maintenances', 'public');
                $entry->images()->create(['image_path' => $path]);
            }

            $entry->update([
                'is_completed'    => true,
                'employee_name'   => $data['employee_name'],
                'completed_date'  => $data['completed_date'],
                'location'        => $data['location'],
                'invoice_number'  => $data['invoice_number'],
            ]);

            // Activar vehículo si estaba desactivado
            if ($entry->car && !$entry->car->is_active) {
                $entry->car->update(['is_active' => 1]);
            }

            return redirect()->route('maintenance.entries.index')->with('success', 'Mantención completada y vehículo activado.');
        }

        public function create()
        {
            $types = MaintenanceType::all();
            $cars = RentalCar::where('is_active', 1)->with(['brand', 'model'])->get();


            return view('tenant.admin.maintenance.entries.create', compact('types', 'cars'));
        }
        public function storeScheduled(Request $request)
        {
            $request->validate([
                'rental_car_id' => 'required|exists:rental_cars,id',
                'maintenance_type_id' => 'required|exists:maintenance_types,id',
                'interval_km' => 'required|integer|min:1',
                'quantity' => 'required|integer|min:1|max:20',
            ]);

            $car = RentalCar::findOrFail($request->rental_car_id);
            $startKm = $car->km;
            $interval = $request->interval_km;
            $quantity = $request->quantity;

            for ($i = 1; $i <= $quantity; $i++) {
                $scheduledKm = $startKm + ($interval * $i);

                Maintenance::create([
                    'rental_car_id' => $car->id,
                    'maintenance_type_id' => $request->maintenance_type_id,
                    'scheduled_km' => $scheduledKm,
                    'is_completed' => false,
                ]);
            }

            return redirect()->route('maintenance.entries.index')->with('success', 'Mantenciones programadas correctamente.');
        }
        public function interruptScheduled(Request $request)
        {
            $request->validate([
                'rental_car_id' => 'required|exists:rental_cars,id',
                'maintenance_type_id' => 'required|exists:maintenance_types,id',
            ]);

            $deletedCount = Maintenance::where('rental_car_id', $request->rental_car_id)
                ->where('maintenance_type_id', $request->maintenance_type_id)
                ->where('is_completed', false)
                ->delete();

            return redirect()->route('maintenance.entries.index')
                ->with('success', "Se han eliminado {$deletedCount} mantenciones programadas futuras.");
        }

        public function store(Request $request)
        {
            $data = $request->validate([
                'rental_car_id'       => 'required|exists:rental_cars,id',
                'maintenance_type_id' => 'required|exists:maintenance_types,id',
                'scheduled_km'        => 'nullable|integer',
                'scheduled_date'      => 'nullable|date',
                'image'               => 'nullable|image|max:2048',
            ]);

            $maintenance = Maintenance::create($data);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('maintenances', 'public');
                $maintenance->images()->create(['image_path' => $path]);
            }

            return redirect()->route('maintenance.entries.index')->with('success', 'Mantención registrada.');
        }

        public function edit(Maintenance $maintenance)
        {
            $types = MaintenanceType::all();
            $cars = RentalCar::all();

            return view('tenant.admin.maintenance.entries.edit', compact('maintenance', 'types', 'cars'));
        }


        public function update(Request $request, Maintenance $maintenance)
        {
            $data = $request->validate([
                'rental_car_id'       => 'required|exists:rental_cars,id',
                'maintenance_type_id' => 'required|exists:maintenance_types,id',
                'scheduled_km'        => 'nullable|integer',
                'scheduled_date'      => 'nullable|date',
                'is_completed'        => 'boolean',
                'employee_name'       => 'nullable|string|max:255',
                'completed_date'      => 'nullable|date',
                'location'            => 'nullable|string|max:255',
                'invoice_number'      => 'nullable|string|max:255',
                'invoice_file'        => 'nullable|image|max:2048',
                'delete_invoice_file' => 'nullable|boolean',
            ]);

            if ($request->delete_invoice_file) {
                foreach ($maintenance->images as $img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
            }

            if ($request->hasFile('invoice_file')) {
                foreach ($maintenance->images as $img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
                $path = $request->file('invoice_file')->store('maintenances', 'public');
                $maintenance->images()->create(['image_path' => $path]);
            }

            $maintenance->update($data);

            return redirect()->route('maintenance.entries.index')->with('success', 'Mantención actualizada.');
        }

    public function destroy(Maintenance $maintenance)
    {
         foreach ($maintenance->images as $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }

        $maintenance->delete();

        return redirect()->route('maintenance.entries.index')->with('success', 'Mantención eliminada.');
    }
}
