<?php

namespace App\Http\Controllers\Tenant\Cost;

use App\Http\Controllers\Controller;
use App\Models\ParkingRegister;
use App\Models\BranchOffice;
use Illuminate\Http\Request;
use App\Models\Cost;
use Yajra\DataTables\Facades\DataTables;

class CostBasicServiceController extends Controller
{
    public function index(Request $request)
    {
        $sucursales = BranchOffice::withoutGlobalScopes()
            ->orderBy('name_branch_offices')
            ->get();

        return view('tenant.admin.cost.index', compact('sucursales'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'branch_office_id' => 'required|exists:branch_offices,id_branch',
            'name'             => 'required|string|max:255',
            'value'            => 'required|numeric',
            'date'             => 'required|date',
            'note'             => 'nullable|string',
        ]);

        try {
            $cost = Cost::create([
                'branch_office_id' => $request->branch_office_id,
                'name'             => $request->name,    // Cambiado
                'value'            => intval($request->value),   // Cambiado
                'date'             => $request->date,    // Cambiado
                'note'             => $request->note,    // Cambiado
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Costo de servicio básico registrado con éxito.',
                'data'    => $cost
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el costo.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function create()
    {
        $sucursales = BranchOffice::orderBy('name_branch_offices')->get();
        return view('tenant.admin.cost.create', compact('sucursales'));
    }

    public function showPage(Request $request)
    {
        $sucursales = BranchOffice::withoutGlobalScopes()
            ->orderBy('name_branch_offices')
            ->get();
        return view('tenant.admin.cost.show', compact('sucursales'));
    }


    public function data(Request $request)
    {
        $costs = Cost::with('branchOffice')->select('cost_basic_services.*');
        return datatables()->of($costs)
            ->addColumn('branch', function($row) {
                return $row->branchOffice ? $row->branchOffice->name_branch_offices : '';
            })
            ->addColumn('actions', function($row) {
        $editBtn = '<a href="' . route('cost_basic_service.edit', $row->id) . '" class="btn btn-outline-warning btn-sm text-dark me-1" title="Editar"><i class="fas fa-edit"></i></a>';
        $deleteBtn = '<button class="btn btn-outline-danger btn-sm text-dark me-1 btn-delete " data-id="'.$row->id.'" title="Eliminar"><i class="fas fa-trash-alt"></i></button>';
        return $editBtn.' '.$deleteBtn;
    })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function edit($id)
    {
        $cost = Cost::findOrFail($id);
        $sucursales = BranchOffice::orderBy('name_branch_offices')->get();
        return view('tenant.admin.cost.edit', compact('cost', 'sucursales'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'branch_office_id' => 'required|exists:branch_offices,id_branch',
            'name'             => 'required|string|max:255',
            'value'            => 'required|numeric',
            'date'             => 'required|date',
            'note'             => 'nullable|string',
        ]);

        $cost = Cost::findOrFail($id);
        $cost->update([
            'branch_office_id' => $request->branch_office_id,
            'name' => $request->name,
            'value'=> intval($request->value),
            'date' => $request->date,
            'note' => $request->note,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Costo actualizado con éxito',
            'data'    => $cost
        ]);
    }

    public function destroy($id)
    {
        $cost = Cost::findOrFail($id);
        $cost->delete();
        return response()->json([
            'success' => true,
            'message' => 'Costo eliminado con éxito'
        ]);
    }
    public function ingresosData(Request $request)
    {
        try {
            $user = auth()->user();
            $isSuperAdmin = $user->hasRole('SuperAdmin');
            $sucursalId = $request->input('sucursal_id');
            $filtroDia = $request->input('filtro_dia');
            $filtroMes = $request->input('filtro_mes');
            $filtroAnio = $request->input('filtro_anio');

            if (empty($sucursalId)) {
                return datatables()->of([])->make(true);
            }

            // Estacionamiento Diario
            $queryDiario = \App\Models\ParkingRegister::where('status', 'paid')
                ->whereHas('registers.register_parking', function ($q) use ($request) {
                    $q->whereHas('parking_service', function ($q2) use ($request) {
                        $q2->where('id_branch_office', $request->sucursal_id)
                            ->where('type_service', 'parking_daily');
                    });
                });

            // Estacionamiento Anual
            $queryAnual = \App\Models\ParkingRegister::where('status', 'paid')
                ->whereHas('registers.register_parking', function ($q) use ($request) {
                    $q->whereHas('parking_service', function ($q2) use ($request) {
                        $q2->where('id_branch_office', $request->sucursal_id)
                            ->where('type_service', 'parking_annual');
                    });
                });

            // Filtro de fecha 
            if ($filtroDia) {
                $queryDiario->whereDate('created_at', $filtroDia);
                $queryAnual->whereDate('created_at', $filtroDia);
            } elseif ($filtroMes) {
                $queryDiario->whereMonth('created_at', $filtroMes);
                $queryAnual->whereMonth('created_at', $filtroMes);
            } elseif ($filtroAnio) {
                $queryDiario->whereYear('created_at', $filtroAnio);
                $queryAnual->whereYear('created_at', $filtroAnio);
            }

            $totalDiario = $queryDiario->sum('total_value');
            $totalAnual = $queryAnual->sum('total_value');

            // Costos
            $costosQuery = \App\Models\Cost::where('branch_office_id', $sucursalId);
            if ($filtroDia) {
                $costosQuery->whereDate('created_at', $filtroDia);
            } elseif ($filtroMes) {
                $costosQuery->whereMonth('created_at', $filtroMes);
            } elseif ($filtroAnio) {
                $costosQuery->whereYear('created_at', $filtroAnio);
            }
            $costos = $costosQuery->get();

            $rows = [];
            // Fila ingresos - Estacionamiento diario
            if ($totalDiario > 0) {
                $rows[] = [
                    'fuente' => 'Estacionamiento diario',
                    'total'  => $totalDiario,
                    'tipo'   => 'ingreso'
                ];
            }
            // Fila ingresos - Estacionamiento anual
            if ($totalAnual > 0) {
                $rows[] = [
                    'fuente' => 'Estacionamiento anual',
                    'total'  => $totalAnual,
                    'tipo'   => 'ingreso'
                ];
            }
            // Fila de costos
            foreach ($costos as $costo) {
                $rows[] = [
                    'fuente' => $costo->name,
                    'total'  => $costo->value,
                    'tipo'   => 'costo'
                ];
            }
            // Fila total operación
            $totalOperacion = ($totalDiario + $totalAnual) - $costos->sum('value');
            $rows[] = [
                'fuente' => 'Total operación',
                'total'  => $totalOperacion,
                'tipo'   => 'operacion'
            ];

            return datatables()->of($rows)->make(true);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}