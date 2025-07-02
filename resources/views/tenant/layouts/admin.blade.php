<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin')</title>
    @php
        $host = request()->getHost();
        $favicon = "storage/tenants/{$host}/imagenes/favicon.png";
    @endphp
    <link rel="icon" href="{{ asset($favicon) }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset($favicon) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed d-flex flex-column min-vh-100">
<div class="wrapper">

    {{-- Navbar --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav align-items-center">
            <li class="nav-item d-none d-sm-inline ml-3">
                <span class="nav-link font-weight-bold">
                    {{ $tenantCompanyName }} - {{ $tenantBranchName }}
                </span>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto align-items-center">
            {{-- Filtros solo en dashboard --}}
        @if(request()->routeIs('analiticas'))
        <ul class="navbar-nav align-items-center ml-3">
            <li class="nav-item dropdown">
                <!-- Botón para desplegar filtros -->
                <button class="btn  dropdown-toggle" type="button" id="dashboardFiltersDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-filter mr-1 "></i> Filtros
                </button>   
                <div class="dropdown-menu p-3" aria-labelledby="dashboardFiltersDropdown" style="min-width:230px;">

                    <div class="form-group mb-2">
                        <label for="filterDateFromGlobal" class="mb-0">Desde</label>
                        <input type="date" id="filterDateFromGlobal" class="form-control form-control-sm">
                    </div>
                    <div class="form-group mb-2">
                        <label for="filterDateToGlobal" class="mb-0">Hasta</label>
                        <input type="date" id="filterDateToGlobal" class="form-control form-control-sm">
                    </div>
                    @if(auth()->user()->hasRole('SuperAdmin'))
                    <div class="form-group mb-0">
                        <label for="branchSelectGlobal" class="mb-0">Sucursal</label>
                        <select id="branchSelectGlobal" class="form-control form-control-sm">
                            @foreach(\App\Models\BranchOffice::all() as $branch)
                                <option value="{{ $branch->id_branch }}">{{ $branch->name_branch_offices }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" id="clearFiltersBtn">
                            <i class="fas fa-eraser mr-1"></i>Limpiar filtros
                        </button>
                    </div>
                </div>
            </li>
        </ul>
        @endif
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    <i class="fas fa-user-circle fa-lg ml-1"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <li class="dropdown-item">
                        <a href="#"
                           class="d-block text-danger"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    {{-- Sidebar --}}
    @include('tenant.layouts.sidebar')

    {{-- Contenido --}}
    <div class="content-wrapper flex-grow-1">
        <section class="content py-4">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    {{-- Footer --}}
    <footer class="main-footer text-sm">
        <strong>&copy; {{ now()->year }} — {{ $tenantCompanyName ?? config('app.name') }}</strong>
    </footer>
</div>

{{-- Logout Form --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

{{-- JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Evita que el dropdown de filtros se cierre al usar selects y fechas --}}
<script>
$(document).on('click', '.dropdown-menu', function (e) {
    if (
        $(e.target).is('select') ||
        $(e.target).is('option') ||
        $(e.target).is('input[type="date"]') ||
        $(e.target).is('input') ||
        $(e.target).is('label')
    ) {
        e.stopPropagation();
    }
});
</script>

@if(request()->routeIs('analiticas'))
<script>
function getFilters() {

    let dateFrom = document.getElementById('filterDateFromGlobal').value;
    let dateTo = document.getElementById('filterDateToGlobal').value;
    let branchId = null;
    @if(auth()->user()->hasRole('SuperAdmin'))
        branchId = document.getElementById('branchSelectGlobal').value;
    @endif
    return { dateFrom, dateTo, branchId };
}

function actualizarGraficos() {
    const { dateFrom, dateTo, branchId } = getFilters();
    if (typeof renderBarChart === "function") renderBarChart(branchId, dateFrom, dateTo);
    if (typeof renderParkingChart === "function") renderParkingChart(branchId, dateFrom, dateTo);
    if (typeof renderLineChart === "function") renderLineChart(branchId, dateFrom, dateTo);
    if (typeof renderCarTypeRanking === "function") renderCarTypeRanking(branchId, dateFrom, dateTo);
    if (typeof fetchTopUsers === "function") fetchTopUsers(branchId, dateFrom, dateTo);
}

document.addEventListener('DOMContentLoaded', function () {
    ['filterDateFromGlobal', 'filterDateToGlobal'].forEach(id => {
        document.getElementById(id).addEventListener('change', actualizarGraficos);
    });
    @if(auth()->user()->hasRole('SuperAdmin'))
    document.getElementById('branchSelectGlobal').addEventListener('change', actualizarGraficos);
    @endif
    actualizarGraficos();
});
</script>
<script>
$(document).on('click', '#clearFiltersBtn', function (e) {
    e.preventDefault();
    location.reload();
});
</script>
@endif

@stack('scripts')
</body>
</html>