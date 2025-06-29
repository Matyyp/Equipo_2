@extends('tenant.layouts.admin')

@section('title', 'Dashboard')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css"/>
<style>
  #rents-table th, #rents-table td {
    vertical-align: middle;
    white-space: nowrap;
  }
  .card-header i {
    margin-right: 8px;
  }
  table.dataTable td,
  table.dataTable th {
    border: none !important;
  }
  table.dataTable tbody tr {
    border: none !important;
  }
  table.dataTable {
    border-top: 2px solid #dee2e6;
    border-bottom: 2px solid #dee2e6;
  }
  .dataTables_paginate .pagination .page-item.active a.page-link {
    background-color: #17a2b8 !important;
    color: #fff !important;
    border-color: #17a2b8 !important;
  }
  .dataTables_paginate .pagination .page-item .page-link {
    background-color: #eeeeee;
    color: #17a2b8 !important;
    border-color: #eeeeee;
  }
  .btn-outline-info.text-info:hover,
  .btn-outline-info.text-info:focus {
    color: #fff !important;
  }
</style>
@endpush
@section('content')
<div class="row">
@if($mantencionesProximas->count())
<div class="col-12 mb-4">
  <div class="card shadow-sm border-start border-4 border-info">
    <div class="card-body">
      <div class="d-flex align-items-center mb-3">
        <i class="fas fa-tools fa-lg text-info me-2 mr-1"> </i>
        <h5 class="mb-0 text-info">
          {{ $mantencionesTotal }}  mantención{{ $mantencionesTotal > 1 ? 'es' : '' }} próxima{{ $mantencionesTotal > 1 ? 's' : '' }} detectada{{ $mantencionesTotal > 1 ? 's' : '' }}
        </h5>
      </div>

      <p class="mb-2 text-muted">Mostrando un resumen por vehículo:</p>

      <ul class="mb-3 ps-3 small">
        @foreach($mantencionesProximas as $m)
          <li class="mb-1">
            <i class="fas fa-car me-1 text-secondary"></i>
            <strong>{{ $m->car->brand->name_brand ?? '-' }} {{ $m->car->model->name_model ?? '-' }}</strong>
            <span class="text-muted">– {{ $m->type->name }}</span>
            @if($m->scheduled_date)
              <span class="badge bg-light text-dark ms-2">
                {{ \Carbon\Carbon::parse($m->scheduled_date)->format('d/m/Y') }}
              </span>
            @endif
          </li>
        @endforeach
      </ul>

      <div class="text-end">
        <a href="{{ route('maintenance.entries.index') }}" class="btn btn-sm btn-outline-info">
          <i class="fas fa-list me-1"></i> Ver todas las mantenciones
        </a>
      </div>
    </div>
  </div>
</div>
@endif




    {{-- Card 1 --}}
    <div class="col-6 col-md-3 mb-4">
        <a href="/estacionamiento/create" class="text-decoration-none">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-car fa-2x mb-2 text-info"></i>
                    <h5 class="card-title text-info">Ingresar Vehículo</h5>
                </div>
            </div>
        </a>
    </div>

    {{-- Card 2 --}}
    <div class="col-6 col-md-3 mb-4">
        <a href="/reservations" class="text-decoration-none">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-calendar-days fa-2x mb-2 text-info"></i>
                    <h5 class="card-title text-info">Reservaciones</h5>
                </div>
            </div>
        </a>
    </div>

    {{-- Card 3 --}}
    <div class="col-6 col-md-3 mb-4">
        <a href="/analiticas" class="text-decoration-none">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-chart-simple fa-2x mb-2 text-info"></i>
                    <h5 class="card-title text-info">Analiticas</h5>
                </div>
            </div>
        </a>
    </div>

    {{-- Card 4 --}}
    <div class="col-6 col-md-3 mb-4">
        <a href="/cost" class="text-decoration-none">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-dollar-sign fa-2x mb-2 text-info"></i>
                    <h5 class="card-title text-info">Ganancias</h5>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    {{-- Gráfico de Estacionamientos --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="bg-white rounded-lg p-4 mx-2">
                <h3 class="text-sm font-semibold mb-2">Disponibilidad Estacionamientos</h3>
                <div class="relative" style="height: 250px;">
                    <canvas id="chartParking"></canvas>
                </div>
                <div id="parkingInfo" class="text-center text-sm mt-3 font-medium text-gray-700"></div>
            </div>
        </div>
    </div>

    {{-- Gráfico de Tipos de Auto --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="bg-white rounded-lg p-4 mx-2">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold">Tipos de auto más arrendados</h3>

                </div>
                <div class="relative" style="height: 250px;">
                    <canvas id="chartCarType"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <h5 class="text-info mb-0">Arriendos de vehículos en progreso</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="rents-table" class="table table-striped w-100">
            <thead>
              <tr>
                <th>RUT</th>
                <th>Cliente</th>
                <th>Auto</th>
                <th>Sucursal</th>
                <th>Desde</th>
                <th>Hasta</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch(`{{ route('analiticas.chart.data') }}`)
        .then(res => res.json())
        .then(data => {
            if (data.parking) {
                const total = data.parking.ocupados + data.parking.disponibles;
                const ocupados = data.parking.ocupados;
                const disponibles = data.parking.disponibles;

                const pieCtx = document.getElementById('chartParking').getContext('2d');
                new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            `Ocupados (${((ocupados / total) * 100).toFixed(1)}%)`,
                            `Disponibles (${((disponibles / total) * 100).toFixed(1)}%)`
                        ],
                        datasets: [{
                            label: 'Estacionamientos',
                            data: [ocupados, disponibles],
                            backgroundColor: ['#ef4444', 'rgba(59, 130, 246, 0.5)'],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: { size: 13 }
                                }
                            }
                        }
                    }
                });

                // Mostrar info adicional
                document.getElementById('parkingInfo').innerHTML = `
                    <div class="mb-1">Ocupados: <span class="text-red-500 font-semibold">${ocupados}</span></div>
                    <div>Disponibles: <span class="text-blue-500 font-semibold">${disponibles}</span></div>
                `;
            }
        })
        .catch(err => console.error(err));
});
</script>
<script>
$(function(){
  $('#rents-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{!! route("dashboard.rents.data") !!}',
    columns: [
      { data: 'client_rut', name: 'client_rut' },
      { data: 'client_name', name: 'client_name' },
      { data: 'auto', name: 'rentalCar.brand.name_brand' },
      { data: 'sucursal', name: 'rentalCar.branchOffice.name_branch_offices' },
      { data: 'start_date', name: 'start_date' },
      { data: 'end_date', name: 'end_date' },
      { data: 'status', name: 'status' },
      { data: 'acciones', name: 'acciones', orderable: false, searchable: false, className: 'text-center' },
    ],
    order: [[4, 'desc']],
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
    responsive: true,
  });
});


</script>
<script>

  let chartCarType = null;


  function renderCarTypeRanking(branchId = null) {
        let url = `{{ route('analiticas.car.type.ranking') }}`;
        @if(auth()->user()->hasRole('SuperAdmin'))
        if (branchId) {
            url += `?branch_id=${branchId}`;
        }
        @endif

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (chartCarType) chartCarType.destroy();
                if (data.labels.length) {
                    const colors = [
                        '#3b82f6', '#f59e42', '#10b981', '#f87171', '#a78bfa',
                        '#fbbf24', '#34d399', '#f472b6', '#6366f1', '#facc15',
                        '#4ade80', '#c026d3', '#eab308', '#f43f5e', '#0ea5e9'
                    ];
                    const borderColors = [
                        '#2563eb', '#ea580c', '#059669', '#dc2626', '#7c3aed',
                        '#b45309', '#059669', '#be185d', '#4f46e5', '#a16207',
                        '#22c55e', '#a21caf', '#ca8a04', '#be123c', '#0369a1'
                    ];
                    const ctx = document.getElementById('chartCarType').getContext('2d');
                    chartCarType = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Cantidad de Arriendos',
                                data: data.values,
                                backgroundColor: colors.slice(0, data.labels.length),
                                borderColor: borderColors.slice(0, data.labels.length),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y',
                            scales: {
                                x: { beginAtZero: true, title: { display: true, text: 'Cantidad' } },
                                y: { title: { display: true, text: 'Tipo de Auto' } }
                            }
                        }
                    });
                }
            })
            .catch(err => console.error(err));
    }
    let chartTopUsers = null;
let currentBranchIdCarType = null;
        renderCarTypeRanking(currentBranchIdCarType);
</script>
@endpush
