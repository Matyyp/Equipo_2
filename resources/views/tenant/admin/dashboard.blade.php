@extends('tenant.layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch("{{ route('dashboard.chart.data') }}")
        .then(res => res.json())
        .then(data => {
            console.log(data); // Diagnóstico

            if (data.labels.length) {
                const ctx = document.getElementById('chartTotalValue').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Ingresos por día',
                            data: data.values,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'Ingresos ($)' } },
                            x: { title: { display: true, text: 'Fecha' } }
                        }
                    }
                });
            }

            // Si existe el objeto "parking", creamos el gráfico de torta
            if (data.parking) {
                const pieCtx = document.getElementById('chartParking').getContext('2d');
                new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Ocupados', 'Disponibles'],
                        datasets: [{
                            label: 'Estacionamientos',
                            data: [data.parking.ocupados, data.parking.disponibles],
                            backgroundColor: ['#ef4444', '#10b981'],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }
        })
        .catch(err => console.error(err));
});

</script>
@endpush


@section('content')
<div class="p-6">
    <div class="grid row gap-6 m-2">
        <!-- Card gráfico de barras -->
        <div class="bg-white rounded-lg shadow p-4 ">
            <h3 class="text-sm font-semibold mb-2">Ingresos por día</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="chartTotalValue"></canvas>
            </div>
        </div>
    
        <!-- Card gráfico de torta -->
        <div class="bg-white rounded-lg shadow p-4 mx-2">
            <h3 class="text-sm font-semibold mb-2">Disponibilidad Estacionamientos</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="chartParking"></canvas>
            </div>
        </div>
    </div>
    
</div>
@endsection

