@extends('tenant.layouts.admin')

@section('title', 'Analiticas')
@section('page_title', 'Analiticas')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let chartBar = null;
    let chartPie = null;
    let chartLine = null;

    function renderBarChart(filter = 'daily', branchId = null) {
        let url = `{{ route('analiticas.chart.data') }}?filter=${filter}`;
        @if(auth()->user()->hasRole('SuperAdmin'))
        if (branchId) {
            url += `&branch_id=${branchId}`;
        }
        @endif

        fetch(url)
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
            })
            .catch(err => console.error(err));
    }

    function renderParkingChart(branchId = null) {
        let url = `{{ route('analiticas.chart.data') }}?filter=daily`;
        @if(auth()->user()->hasRole('SuperAdmin'))
        if (branchId) {
            url += `&branch_id=${branchId}`;
        }
        @endif

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (chartPie) chartPie.destroy();
                if (data.parking) {
                    const total = data.parking.ocupados + data.parking.disponibles;
                    const ocupados = data.parking.ocupados;
                    const disponibles = data.parking.disponibles;

                    const pieCtx = document.getElementById('chartParking').getContext('2d');
                    chartPie = new Chart(pieCtx, {
                        type: 'doughnut',
                        data: {
                            labels: [
                                `Ocupados\n(${total > 0 ? ((ocupados / total) * 100).toFixed(1) : 0}%)`,
                                `Disponibles\n(${total > 0 ? ((disponibles / total) * 100).toFixed(1) : 0}%)`
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
                    document.getElementById('parkingInfo').innerHTML = `
                        <div class="mb-1">Ocupados: <span class="text-red-500 font-semibold">${ocupados}</span></div>
                        <div>Disponibles: <span class="text-blue-500 font-semibold">${disponibles}</span></div>
                    `;
                } else {
                    document.getElementById('parkingInfo').innerHTML = '';
                }
            })
            .catch(err => console.error(err));
    }

    function renderLineChart(filter = 'daily', branchId = null) {
        let url = `{{ route('analiticas.chart.line.data') }}?filter=${filter}`;
        @if(auth()->user()->hasRole('SuperAdmin'))
        if (branchId) {
            url += `&branch_id=${branchId}`;
        }
        @endif

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (chartLine) chartLine.destroy();

                if (data.labels.length) {
                    const ctx = document.getElementById('chartLineal').getContext('2d');
                    chartLine = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: 'Estacionamientos',
                                    data: data.parkingValues,
                                    borderColor: 'rgba(59,130,246,1)',
                                    backgroundColor: 'rgba(59,130,246,0.2)',
                                    tension: 0.3,
                                    fill: false,
                                    pointRadius: 3,
                                },
                                {
                                    label: 'Rentas',
                                    data: data.rentValues,
                                    borderColor: 'rgba(239,68,68,1)',
                                    backgroundColor: 'rgba(239,68,68,0.2)',
                                    tension: 0.3,
                                    fill: false,
                                    pointRadius: 3,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: { display: true, text: 'Precio ($)' }
                                },
                                x: {
                                    title: { display: true, text: filter === 'weekly' ? 'Semana' : 'Fecha' }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                }
                            }
                        },
                    });
                }
            })
            .catch(err => console.error(err));
    }

    // Inicialización: cada gráfico con su propio filtro de sucursal
    @if(auth()->user()->hasRole('SuperAdmin'))
        let currentBranchIdBar = document.getElementById('branchSelectBar').value;
        let currentBranchIdLineal = document.getElementById('branchSelectLineal').value;
        let currentBranchIdParking = document.getElementById('branchSelectParking').value;
    @else
        let currentBranchIdBar = null;
        let currentBranchIdLineal = null;
        let currentBranchIdParking = null;
    @endif

    renderBarChart(document.getElementById('filterType').value, currentBranchIdBar);
    renderParkingChart(currentBranchIdParking);
    renderLineChart(document.getElementById('filterType').value, currentBranchIdLineal);

    document.getElementById('filterType').addEventListener('change', function () {
        renderBarChart(this.value, currentBranchIdBar);
        renderLineChart(this.value, currentBranchIdLineal);
    });

    @if(auth()->user()->hasRole('SuperAdmin'))
    document.getElementById('branchSelectBar').addEventListener('change', function () {
        currentBranchIdBar = this.value;
        renderBarChart(document.getElementById('filterType').value, currentBranchIdBar);
    });
    document.getElementById('branchSelectLineal').addEventListener('change', function () {
        currentBranchIdLineal = this.value;
        renderLineChart(document.getElementById('filterType').value, currentBranchIdLineal);
    });
    document.getElementById('branchSelectParking').addEventListener('change', function () {
        currentBranchIdParking = this.value;
        renderParkingChart(currentBranchIdParking);
    });
    @endif
});
</script>
@endpush

@section('content')
<div class="p-6">
    <div class="grid row gap-6 m-2">
        <!-- Card gráfico de barras -->
        <div class="bg-white rounded-lg shadow p-4 mr-2 mb-2">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-sm font-semibold">Ingresos</h3>
                <div class="flex items-center gap-2">
                    <select id="filterType" class="border border-gray-300 rounded text-sm px-2 py-1">
                        <option value="daily">Diario</option>
                        <option value="weekly">Semanal</option>
                    </select>
                    @if(auth()->user()->hasRole('SuperAdmin'))
                        <span class="text-sm">Sucursal:</span>
                        <select id="branchSelectBar" class="border border-gray-300 rounded text-sm px-2 py-1">
                            @foreach(\App\Models\BranchOffice::all() as $branch)
                                <option value="{{ $branch->id_branch }}">{{ $branch->name_branch_offices }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
            <div class="relative" style="height: 250px;">
                <canvas id="chartTotalValue"></canvas>
            </div>
        </div>

        <!-- Card gráfico lineal -->
        <div class="bg-white rounded-lg shadow p-4 mb-2">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-sm font-semibold">Desempeño: Estacionamientos (azul) vs Rentas (rojo)</h3>
                @if(auth()->user()->hasRole('SuperAdmin'))
                    <div class="flex items-center gap-2">
                        <span class="text-sm">Sucursal:</span>
                        <select id="branchSelectLineal" class="border border-gray-300 rounded text-sm px-2 py-1">
                            @foreach(\App\Models\BranchOffice::all() as $branch)
                                <option value="{{ $branch->id_branch }}">{{ $branch->name_branch_offices }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
            <div class="relative" style="height: 250px;">
                <canvas id="chartLineal"></canvas>
            </div>
        </div>
        
        <!-- Card gráfico de torta -->
        <div class="bg-white rounded-lg shadow p-4 mb-2 mx-2">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-sm font-semibold">Disponibilidad Estacionamientos</h3>
                @if(auth()->user()->hasRole('SuperAdmin'))
                    <div class="flex items-center gap-2">
                        <span class="text-sm">Sucursal:</span>
                        <select id="branchSelectParking" class="border border-gray-300 rounded text-sm px-2 py-1">
                            @foreach(\App\Models\BranchOffice::all() as $branch)
                                <option value="{{ $branch->id_branch }}">{{ $branch->name_branch_offices }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
            <div class="relative" style="height: 250px;">
                <canvas id="chartParking"></canvas>
            </div>
            <div id="parkingInfo" class="text-center text-sm mt-3 font-medium text-gray-700"></div>
        </div>
    </div>
</div> 
@endsection