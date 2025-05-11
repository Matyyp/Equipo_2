@extends('tenant.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    {{-- Card 1 --}}
    <div class="col-6 col-md-3 mb-4">
        <a href="/estacionamiento/create" class="text-decoration-none">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-car fa-2x mb-2 text-primary"></i>
                    <h5 class="card-title">Ingresar Vehículo</h5>
                </div>
            </div>
        </a>
    </div>

    {{-- Card 2: en proceso --}}
    <div class="col-6 col-md-3 mb-4">
        <div class="card shadow-sm h-100 text-center text-secondary">
            <div class="card-body d-flex flex-column justify-content-center">
                <h5 class="card-title">Reservas</h5>
                <span class="badge bg-secondary">En proceso</span>
            </div>
        </div>
    </div>

    {{-- Card 3: en proceso --}}
    <div class="col-6 col-md-3 mb-4">
        <div class="card shadow-sm h-100 text-center text-secondary">
            <div class="card-body d-flex flex-column justify-content-center">
                <h5 class="card-title">Por definir</h5>
                <span class="badge bg-secondary">En proceso</span>
            </div>
        </div>
    </div>

    {{-- Card 4 --}}
    <div class="col-6 col-md-3 mb-4">
        <a href="analiticas" class="text-decoration-none">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                    <h5 class="card-title">Ganancias</h5>
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
