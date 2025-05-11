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

        {{-- Card 4: en proceso --}}

        <div class="col-6 col-md-3 mb-4">
            <a href="#" class="text-decoration-none">
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
                <div class="card-body">
                    {{-- Aquí insertarás tu gráfico de barras --}}
                    <div id="chartBarPlaceholder" style="height: 250px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    {{-- Aquí insertarás tu gráfico circular --}}
                    <div id="chartDonutPlaceholder" style="height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
