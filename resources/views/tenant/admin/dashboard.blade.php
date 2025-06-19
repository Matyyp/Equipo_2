@extends('tenant.layouts.admin')

@section('title', 'Dashboard')

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

{{-- Espacio para gráficos --}}
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="bg-white rounded-lg  p-4 mx-2">
            <h3 class="text-sm font-semibold mb-2">Disponibilidad Estacionamientos</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="chartParking"></canvas>
            </div>
            <div id="parkingInfo" class="text-center text-sm mt-3 font-medium text-gray-700">

            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
@endpush
