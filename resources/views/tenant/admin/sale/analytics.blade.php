@extends('tenant.layouts.admin')

@section('title', 'Analiticas')
@section('page_title', 'Analiticas')
@push('styles')
<style>
.btn-outline-info.text-info:hover,
.btn-outline-info.text-info:focus {
    color: #fff !important;
}
#userRankingList {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f9fafb;
    overflow: hidden;
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-top: 0;
}

#userRankingList li,
.user-header {
    display: flex;
    align-items: center;
    gap: 0;
    padding: 0;
    min-height: 44px;
    border-bottom: 1px solid #e5e7eb;
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
    min-height: 38px;
    cursor: default;
    gap: 0;
}

.user-header > div,
#userRankingList li > div {
    display: flex;
    align-items: left;
    justify-content: center;
    height: 100%;
}

/* Columna # */
.header-rank,
.user-ranking-number {
    width: 50px;
    min-width: 50px;
    max-width: 50px;
    text-align: center;
    justify-content: center;
    align-items: center;
}

/* Columna Usuario - alineada a la izquierda */
.header-user,
.user-info {
    flex: 1 1 0%;
    min-width: 0;
    text-align: left;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    padding-left: 12px;
    padding-right: 0;
    gap: 2px;
}

/* Columna Cant. de Arriendos */
.header-count,
.user-right {
    width: 130px;
    min-width: 130px;
    max-width: 130px;
    text-align: center;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

/* Columna Valoración */
.header-rating,
.user-rating-col {
    width: 110px;
    min-width: 110px;
    max-width: 110px;
    text-align: center;
    justify-content: center;
    align-items: center;
}

.user-row,
.user-meta {
    width: 100%;
    text-align: left;
    justify-content: flex-start;
}

.user-ranking-number {
    font-weight: bold;
    color: #64748b;
    font-size: 1.05rem;
    display: flex;
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
    font-size: 1rem;
    color: #10b981;
    font-weight: bold;
    padding-right: 0;
    padding-left: 0;
}

.user-rating-btn {
    background: none;
    border: none;
    color: #fbbf24;
    cursor: pointer;
    padding: 0 6px;
    font-size: 1.3rem;
    transition: color 0.15s;
}
.user-rating-btn:hover {
    color: #f59e42;
}
.star-icon {
    color: #fbbf24;
    font-size: 1.3rem;
    margin-left: 3px;
    vertical-align: middle;
}

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
.contenedor-padre {
  display: flex;
  flex-direction: column;       /* Apila los hijos verticalmente */
  align-items: center;          /* Centra horizontalmente */
  gap: 20px;
  padding: 20px;
  background-color: #f8f9fa;
  max-width: 100%;            /* Controla el ancho total */
  margin: 0 auto;               /* Centra el contenedor en la página */
  width: 100%;
}

/* Hacemos que los dos contenedores tengan el mismo ancho que el padre */
.contenedor,
.contenedor2 {
  display: flex;
  flex-wrap: wrap;              /* Permite varias filas */
  justify-content: center;
  align-items: flex-start;
  gap: 20px;
  width: 100%;
}

/* Estilos para todas las cards gráficas */
.contenedor > div {
  flex: 1 1 calc(25% - 20px);   /* 4 columnas iguales con espacio */
  min-width: 300px;             /* Evita que se achiquen demasiado en pantallas pequeñas */
  max-width: 100%;
  height: 420px;                /* Altura uniforme */
  box-sizing: border-box;
  background-color: white;
}

/* Asegura que el último gráfico también use el mismo ancho */
.contenedor2 > div {
  width: 100%;
  max-width: 100%;
  box-sizing: border-box;
}

</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://kit.fontawesome.com/96b81e7f85.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    let filterType = 'daily'; 
    let branchId = null;
    @if(auth()->user()->hasRole('SuperAdmin'))
        branchId = document.getElementById('branchSelectGlobal').value;
    @endif

    function getDateFilters() {
        return {
            dateFrom: document.getElementById('filterDateFromGlobal') ? document.getElementById('filterDateFromGlobal').value : null,
            dateTo: document.getElementById('filterDateToGlobal') ? document.getElementById('filterDateToGlobal').value : null
        };
    }

    function actualizarGraficos() {
        const { dateFrom, dateTo } = getDateFilters();
        renderBarChart(filterType, branchId, dateFrom, dateTo);
        renderParkingChart(branchId, dateFrom, dateTo);
        renderLineChart(filterType, branchId, dateFrom, dateTo);
        renderCarTypeRanking(branchId, dateFrom, dateTo);
        fetchTopUsers(branchId, dateFrom, dateTo);
    }

   

    @if(auth()->user()->hasRole('SuperAdmin'))
    document.getElementById('branchSelectGlobal').addEventListener('change', function () {
        branchId = this.value;
        actualizarGraficos();
    });
    @endif

    if (document.getElementById('filterDateFromGlobal')) {
        document.getElementById('filterDateFromGlobal').addEventListener('change', actualizarGraficos);
    }
    if (document.getElementById('filterDateToGlobal')) {
        document.getElementById('filterDateToGlobal').addEventListener('change', actualizarGraficos);
    }

    // BARRAS
    function renderBarChart(filter, branchId, dateFrom, dateTo) {
        let url = `/analiticas/chart-data?filter=${filter}`;
        if (dateFrom) url += `&date_from=${dateFrom}`;
        if (dateTo) url += `&date_to=${dateTo}`;
        if (branchId) url += `&branch_id=${branchId}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (window.chartBar) window.chartBar.destroy();
                if (data.labels && data.values && data.labels.length) {
                    const ctx = document.getElementById('chartTotalValue').getContext('2d');
                    window.chartBar = new Chart(ctx, {
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
                                y: { beginAtZero: true, title: { display: true, text: 'Ingresos ($)' } },
                                x: { title: { display: true, text: filter === 'weekly' ? 'Semana' : 'Fecha' } }
                            }
                        }
                    });
                }
            })
            .catch(err => console.error('BarChart:', err));
    }

    // TORTA
    function renderParkingChart(branchId, dateFrom, dateTo) {
        let url = `/analiticas/chart-data?filter=daily`;
        if (dateFrom) url += `&date_from=${dateFrom}`;
        if (dateTo) url += `&date_to=${dateTo}`;
        if (branchId) url += `&branch_id=${branchId}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (window.chartPie) window.chartPie.destroy();
                if (data.parking) {
                    const total = data.parking.ocupados + data.parking.disponibles;
                    const ocupados = data.parking.ocupados;
                    const disponibles = data.parking.disponibles;
                    const pieCtx = document.getElementById('chartParking').getContext('2d');
                    window.chartPie = new Chart(pieCtx, {
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
            .catch(err => console.error('ParkingChart:', err));
    }

    // LINEAL
    function renderLineChart(filter, branchId, dateFrom, dateTo) {
        let url = `/analiticas/chart-line-data?filter=${filter}`;
        if (dateFrom) url += `&date_from=${dateFrom}`;
        if (dateTo) url += `&date_to=${dateTo}`;
        if (branchId) url += `&branch_id=${branchId}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (window.chartLine) window.chartLine.destroy();
                if (data.labels && data.labels.length) {
                    const ctx = document.getElementById('chartLineal').getContext('2d');
                    window.chartLine = new Chart(ctx, {
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
                                y: { beginAtZero: true, title: { display: true, text: 'Precio ($)' } },
                                x: { title: { display: true, text: filter === 'weekly' ? 'Semana' : 'Fecha' } }
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
            .catch(err => console.error('LineChart:', err));
    }

    // RANKING AUTOS
    function renderCarTypeRanking(branchId, dateFrom, dateTo) {
    let url = `{{ route('analiticas.car.type.ranking') }}`;
    let params = [];
    @if(auth()->user()->hasRole('SuperAdmin'))
        if (branchId) params.push(`branch_id=${branchId}`);
    @endif
    if (dateFrom) params.push(`date_from=${dateFrom}`);
    if (dateTo) params.push(`date_to=${dateTo}`);
    if (params.length) url += '?' + params.join('&');

    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (window.chartCarType && typeof window.chartCarType.destroy === 'function') {
                window.chartCarType.destroy();
            }
            if (data.labels && data.values && data.labels.length) {
                const colors = ['#3b82f6','#f59e42','#10b981','#f87171','#a78bfa'];
                const borderColors = ['#2563eb','#ea580c','#059669','#dc2626','#7c3aed'];
                const ctx = document.getElementById('chartCarType').getContext('2d');
                window.chartCarType = new Chart(ctx, {
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
            } else {
                // Limpia el canvas si no hay datos
                const ctx = document.getElementById('chartCarType').getContext('2d');
                ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
            }
        })
        .catch(err => {
            console.error('Error en renderCarTypeRanking:', err);
        });
}

    // RANKING USUARIOS
    function fetchTopUsers(branchId, dateFrom, dateTo) {
        let url = `/analiticas/top-users-ranking`;
        let params = [];
        if (branchId) params.push(`branch_id=${branchId}`);
        if (dateFrom) params.push(`date_from=${dateFrom}`);
        if (dateTo) params.push(`date_to=${dateTo}`);
        if (params.length) url += '?' + params.join('&');

        fetch(url)
            .then(res => res.json())
            .then(data => {
                renderUserList(data.users);
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
            <div class="header-rating">Valoración</div>
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
                    <button class="btn btn-outline-info btn-sm text-info ml-1 " data-client-rut="${u.client_rut}" data-client-name="${u.client_name}" title="Ver calificaciones">
                        <i class="fas fa-star"></i>
                    </button>
                </div>
            `;
            li.querySelector('.btn-outline-info').addEventListener('click', function() {
                showUserRatings(this.getAttribute('data-client-rut'), this.getAttribute('data-client-name'));
            });
            ul.appendChild(li);
        });
    }

    // MODAL LOGIC
    function showUserRatings(clientRut, clientName) {
        let url = `/analiticas/user-ratings/${clientRut}`;
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
    document.getElementById('userRatingsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Inicialización general
    actualizarGraficos();
});
</script>
@endpush

@section('content')
<div class="p-0">
    <div class="contenedor-padre ">
        <div class="contenedor ">
            <!-- Card gráfico de barras -->
            <div class="bg-white  rounded-lg shadow p-4  mb-2">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold">Ingresos estacionamiento</h3>
                </div>
                <div class="relative" style="height: 320px;">
                    <canvas id="chartTotalValue"></canvas>
                </div>
            </div>
            <!-- Card ranking de autos más arrendados -->
            <div class="bg-white rounded-lg shadow p-4   mb-2">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold">Tipos de vehículos más arrendados</h3>
                </div>
                <div class="relative" style="height: 320px;">
                    <canvas id="chartCarType"></canvas>
                </div>
            </div>
            <!-- Card gráfico de torta -->
            <div class="bg-white rounded-lg shadow p-4  mb-2">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold">Disponibilidad estacionamientos</h3>
                </div>
                <div class="relative" style="height: 250px;">
                    <canvas id="chartParking"></canvas>
                </div>
                <div id="parkingInfo" class="text-center text-sm mt-3 font-medium text-gray-700"></div>
            </div>
            <!-- Card gráfico lineal -->
            <div class="bg-white  rounded-lg shadow p-4  mb-2">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold">Estacionamientos (azul) vs Rentas (rojo)</h3>
                </div>
                <div class="relative" style="height: 320px;">
                    <canvas id="chartLineal"></canvas>
                </div>
            </div>
        </div>
        <div class="contenedor2 ">    
            <div class="bg-white rounded-lg shadow p-4 mb-2 ">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold">Usuarios que más arrendaron vehículos</h3>
                </div>
                <ul id="userRankingList" class="divide-y divide-gray-200"></ul>
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
</div> 
@endsection