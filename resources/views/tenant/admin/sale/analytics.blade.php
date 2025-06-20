@extends('tenant.layouts.admin')

@section('title', 'Analiticas')
@section('page_title', 'Analiticas')
@push('styles')
<style>
#userRankingList {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f9fafb;
    overflow: hidden;
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-top: 0;
}
#userRankingList li, #userRankingList .user-header {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #e5e7eb;
}
#userRankingList li {
    transition: background 0.2s;
    cursor: pointer;
    padding: 0;
    min-height: 44px;
}
#userRankingList li:last-child {
    border-bottom: none;
}
#userRankingList li:hover {
    background: #e5e7eb;
}
.user-header {
    font-weight: 700;
    color: #374151;
    font-size: 0.97rem;
    background: #f3f4f6;
    border-bottom: 2px solid #e5e7eb;
    padding: 0;
    cursor: default;
    min-height: 38px;
}
.user-header > div {
    padding: 10px 0 10px 0;
    display: flex;
    align-items: center;
}
.user-header .header-rank   { width: 32px; min-width: 32px; text-align: center;}
.user-header .header-user   { flex: 1; padding-left: 8px;}
.user-header .header-count  { width: 92px; min-width: 92px; text-align: right;}
.user-header .header-rating { width: 60px; min-width: 60px; text-align: right;}
.user-ranking-number {
    width: 32px;
    min-width: 32px;
    text-align: center;
    font-weight: bold;
    color: #64748b;
    font-size: 1.05rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.user-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 2px;
    padding: 12px 0 12px 12px; /* <-- Cambiado aquí el último valor */
}
.user-row {
    font-weight: 600;
    font-size: 1rem;
    color: #22223b;
    display: flex;
    align-items: center;
    gap: 6px;
}
.user-meta {
    color: #6b7280;
    font-size: 0.95rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.user-right {
    min-width: 92px;
    width: 92px;
    text-align: right;
    font-size: 1rem;
    color: #10b981;
    font-weight: bold;
    padding-right: 8px;
    padding-left: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.user-rating-col {
    width: 60px;
    min-width: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-right: 8px;
}
.user-rating-btn {
    background: none;
    border: none;
    color: #3b82f6;
    cursor: pointer;
    padding: 0 6px;
    font-size: 1.1rem;
    transition: color 0.15s;
}
.user-rating-btn:hover {
    color: #1d4ed8;
}
.star-icon {
    color: #fbbf24;
    font-size: 1.1rem;
    margin-left: 3px;
}
.user-header {
    display: flex;
    font-weight: 700;
    color: #374151;
    font-size: 0.97rem;
    background: #f3f4f6;
    border-bottom: 2px solid #e5e7eb;
    padding: 0;
    cursor: default;
    min-height: 38px;
    gap: 16px; /* <-- Espacio horizontal entre columnas */
}
.user-header > div {
    padding: 10px 0 10px 0;
    display: flex;
    align-items: center;
    
}

.user-header .header-rank   { width: 32px; min-width: 32px; text-align: center;padding-left: 10px;}
.user-header .header-user   { flex: 1; padding-left: 12px;}  
.user-header .header-count  { width: 110px; min-width: 92px; text-align: right; padding-left: 12px;}
.user-header .header-rating { width: 70px; min-width: 60px; text-align: right; padding-left: 12px;}
#userRatingsModal {
    position: fixed;
    z-index: 50;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.35);
}
#userRatingsModal.hidden { display: none !important; }
.modal-content {
    background: #fff;
    border-radius: 10px;
    max-width: 420px;
    width: 97vw;
    min-height: 120px;
    max-height: 85vh;
    padding: 24px 20px 20px 20px;
    position: relative;
    box-shadow: 0 6px 32px 0 rgba(0,0,0,.20);
    overflow-y: auto;
}
.close-modal-btn {
    position: absolute;
    top: 12px;
    right: 18px;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #999;
    cursor: pointer;
    z-index: 12;
    transition: color 0.2s;
}
.close-modal-btn:hover { color: #e11d48; }
.rating-title {
    font-size: 1.1rem; font-weight: bold; margin-bottom: 8px;
}
.rating-block {
    border-bottom: 1px solid #e5e7eb;
    padding: 10px 0;
    font-size: 0.98rem;
}
.rating-block:last-child { border-bottom: none; }
.rating-stars {
    color: #fbbf24;
    font-size: 1.15rem;
    letter-spacing: .07em;
    margin-bottom: 2px;
}
.rating-label { font-weight: 600; }
.rating-empty { color: #999; font-style: italic; }
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let chartBar = null;
    let chartPie = null;
    let chartLine = null;
    let chartCarType = null;

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

    // Gráfico ranking de autos más arrendados
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




document.getElementById('closeUserRatingsModal').addEventListener('click', function() {
    document.getElementById('userRatingsModal').classList.add('hidden');
});

@if(auth()->user()->hasRole('SuperAdmin'))
    let currentBranchIdUsers = document.getElementById('branchSelectUsers').value;
    document.getElementById('branchSelectUsers').addEventListener('change', function () {
        currentBranchIdUsers = this.value;
        renderTopUsersRanking(currentBranchIdUsers);
    });
@else
    let currentBranchIdUsers = null;
@endif


    
    // Inicialización: cada gráfico con su propio filtro de sucursal
    @if(auth()->user()->hasRole('SuperAdmin'))
        let currentBranchIdBar = document.getElementById('branchSelectBar').value;
        let currentBranchIdLineal = document.getElementById('branchSelectLineal').value;
        let currentBranchIdParking = document.getElementById('branchSelectParking').value;
        let currentBranchIdCarType = document.getElementById('branchSelectCarType').value;

    @else
        let currentBranchIdBar = null;
        let currentBranchIdLineal = null;
        let currentBranchIdParking = null;
        let currentBranchIdCarType = null;

    @endif

    renderBarChart(document.getElementById('filterType').value, currentBranchIdBar);
    renderParkingChart(currentBranchIdParking);
    renderLineChart(document.getElementById('filterType').value, currentBranchIdLineal);
    renderCarTypeRanking(currentBranchIdCarType);


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
    document.getElementById('branchSelectCarType').addEventListener('change', function () {
        currentBranchIdCarType = this.value;
        renderCarTypeRanking(currentBranchIdCarType);
    });
    @endif
});
</script>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let allUsers = [];

    function fetchTopUsers(branchId = null) {
        let url = `{{ route('analiticas.top.users.ranking') }}`;
        @if(auth()->user()->hasRole('SuperAdmin'))
        if (branchId) {
            url += `?branch_id=${branchId}`;
        }
        @endif

        fetch(url)
            .then(res => res.json())
            .then(data => {
                allUsers = data.users;
                renderUserList(allUsers);
            });
    }

    function renderUserList(users) {
        const ul = document.getElementById('userRankingList');
        ul.innerHTML = '';

        // Header row
        const header = document.createElement('div');
        header.className = 'user-header';
        header.innerHTML = `
            <div class="header-rank">#</div>
            <div class="header-user">Usuario</div>
            <div class="header-count">Cant. de Arriendos</div>
            <div class="header-rating">Rating</div>
        `;
        ul.appendChild(header);

        users.forEach((u, idx) => {
            const li = document.createElement('li');
            li.innerHTML = `
                <div class="user-ranking-number">${idx + 1}</div>
                <div class="user-info">
                    <div class="user-row">${u.client_name}</div>
                    <div class="user-meta">${u.client_email || ""}</div>
                </div>
                <div class="user-right" title="Cantidad de arriendos">${u.total}</div>
                <div class="user-rating-col">
                    <button class="user-rating-btn" data-client-rut="${u.client_rut}" data-client-name="${u.client_name}" title="Ver calificaciones">
                        <span class="star-icon">&#9733;</span>
                    </button>
                </div>
            `;
            li.querySelector('.user-rating-btn').addEventListener('click', function() {
                showUserRatings(this.getAttribute('data-client-rut'), this.getAttribute('data-client-name'));
            });
            ul.appendChild(li);
        });
    }

    document.getElementById('userRankingSearch').addEventListener('input', function() {
        const value = this.value.toLowerCase();
        const filtered = allUsers.filter(u => 
            u.client_name.toLowerCase().includes(value) ||
            (u.client_email && u.client_email.toLowerCase().includes(value))
        );
        renderUserList(filtered);
    });

    // MODAL LOGIC
    function showUserRatings(clientRut, clientName) {
        let url = `{{ url('analiticas/user-ratings') }}/${clientRut}`;
        fetch(url)
            .then(res => res.json())
            .then(data => {
                let html = `<div class="rating-title">${clientName}</div>`;
                if (data.ratings.length) {
                    data.ratings.forEach(r => {
                        html += `<div class="rating-block">
                            <div class="rating-stars">${'★'.repeat(r.stars)}${'☆'.repeat(5-r.stars)}</div>
                            <div><span class="rating-label">Criterio:</span> ${r.criterio ?? '<span class="rating-empty">-</span>'}</div>
                            <div><span class="rating-label">Comentario:</span> ${r.comentario ?? '<span class="rating-empty">-</span>'}</div>
                        </div>`;
                    });
                } else {
                    html += '<div class="rating-block rating-empty">No hay calificaciones para este usuario.</div>';
                }
                document.getElementById('userRatingsList').innerHTML = html;
                document.getElementById('userRatingsModal').classList.remove('hidden');
            });
    }
    document.getElementById('closeUserRatingsModal').addEventListener('click', function() {
        document.getElementById('userRatingsModal').classList.add('hidden');
    });
    // Cierra el modal si se hace click fuera del contenido
    document.getElementById('userRatingsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    @if(auth()->user()->hasRole('SuperAdmin'))
        let currentBranchIdUsers = document.getElementById('branchSelectUsers') ? document.getElementById('branchSelectUsers').value : null;
        if (document.getElementById('branchSelectUsers')) {
            document.getElementById('branchSelectUsers').addEventListener('change', function () {
                currentBranchIdUsers = this.value;
                fetchTopUsers(currentBranchIdUsers);
            });
        }
        fetchTopUsers(currentBranchIdUsers);
    @else
        fetchTopUsers();
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

        <!-- Card ranking de autos más arrendados -->
        <div class="bg-white rounded-lg shadow p-4 mb-2 mx-2">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-sm font-semibold">Ranking Tipos de Auto Más Arrendados</h3>
                @if(auth()->user()->hasRole('SuperAdmin'))
                    <div class="flex items-center gap-2">
                        <span class="text-sm">Sucursal:</span>
                        <select id="branchSelectCarType" class="border border-gray-300 rounded text-sm px-2 py-1">
                            @foreach(\App\Models\BranchOffice::all() as $branch)
                                <option value="{{ $branch->id_branch }}">{{ $branch->name_branch_offices }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
            <div class="relative" style="height: 250px;">
                <canvas id="chartCarType"></canvas>
            </div>
        </div>
        <div class="bg-white col-6 rounded-lg shadow p-4 mb-2 ">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-sm font-semibold">Ranking Usuarios que Más Arrendaron Autos</h3>
                @if(auth()->user()->hasRole('SuperAdmin'))
                    <div class="flex items-center gap-2">
                        <span class="text-sm">Sucursal:</span>
                        <select id="branchSelectUsers" class="border border-gray-300 rounded text-sm px-2 py-1">
                            @foreach(\App\Models\BranchOffice::all() as $branch)
                                <option value="{{ $branch->id_branch }}">{{ $branch->name_branch_offices }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
            <div class="bg-gray-100 rounded-lg shadow p-4 w-full max-w-md mx-auto">
                <div class="mb-3">
                    <input type="text" id="userRankingSearch" placeholder="Buscar usuario..." class="w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring">
                </div>
                <ul id="userRankingList" class="divide-y divide-gray-200"></ul>
            </div>
        </div>

        <!-- Modal para calificaciones -->
        <div id="userRatingsModal" class="hidden">
            <div class="modal-content">
                <button type="button" class="close-modal-btn" id="closeUserRatingsModal" aria-label="Cerrar">&times;</button>
                <div id="userRatingsList"></div>
            </div>
        </div>


    </div>
</div> 
@endsection