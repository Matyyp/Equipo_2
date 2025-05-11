@extends('tenant.layouts.admin')

@section('title', 'Analiticas')
@section('page_title', 'Analiticas')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let chartBar = null;

    function renderBarChart(filter = 'daily') {
        fetch(`{{ route('analiticas.chart.data') }}?filter=${filter}`)
            .then(res => res.json())
            .then(data => {
                if (chartBar) chartBar.destroy();

                if (data.labels.length) {
                    const ctx = document.getElementById('chartTotalValue').getContext('2d');
                    chartBar = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: `Ingresos por ${filter === 'weekly' ? 'semana' : 'día'}`,
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
                                y: {
                                    beginAtZero: true,
                                    title: { display: true, text: 'Ingresos ($)' }
                                },
                                x: {
                                    title: { display: true, text: filter === 'weekly' ? 'Semana' : 'Fecha' }
                                }
                            }
                        }
                    });
                }

                // Gráfico de torta
                if (data.parking) {
                    const total = data.parking.ocupados + data.parking.disponibles;
                    const ocupados = data.parking.ocupados;
                    const disponibles = data.parking.disponibles;

                    const pieCtx = document.getElementById('chartParking').getContext('2d');
                    new Chart(pieCtx, {
                        type: 'doughnut',
                        data: {
                            labels: [
                                `Ocupados\n(${((ocupados / total) * 100).toFixed(1)}%)`,
                                `Disponibles\n(${((disponibles / total) * 100).toFixed(1)}%)`
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
                            layout: { padding: { top: 10 } },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'start',
                                    labels: {
                                        boxWidth: 20,
                                        boxHeight: 10,
                                        padding: 12,
                                        usePointStyle: false,
                                        font: { size: 13 },
                                        generateLabels: function (chart) {
                                            const data = chart.data;
                                            return data.labels.map((label, i) => ({
                                                text: label,
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                index: i
                                            }));
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Info adicional debajo del gráfico
                    document.getElementById('parkingInfo').innerHTML = `
                        <div class="mb-1">Ocupados: <span class="text-red-500 font-semibold">${ocupados}</span></div>
                        <div>Disponibles: <span class="text-blue-500 font-semibold">${disponibles}</span></div>
                    `;
                }
            })
            .catch(err => console.error(err));
    }

    // Cargar gráfico inicial
    renderBarChart();

    // Cambiar filtro
    document.getElementById('filterType').addEventListener('change', function () {
        renderBarChart(this.value);
    });
});
</script>
@endpush

@section('content')
<div class="p-6">
    <div class="grid row gap-6 m-2">
        <!-- Card gráfico de barras -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-sm font-semibold">Ingresos</h3>
                <select id="filterType" class="border border-gray-300 rounded text-sm px-2 py-1">
                    <option value="daily">Diario</option>
                    <option value="weekly">Semanal</option>
                </select>
            </div>
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
            <div id="parkingInfo" class="text-center text-sm mt-3 font-medium text-gray-700">

            </div>
        </div>
    </div>
</div>
@endsection
