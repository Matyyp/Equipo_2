@extends('tenant.layouts.admin')

@section('title', 'Detalle del Arriendo')
@section('page_title', 'Detalle del Registro de Arriendo')

@section('content')
<div class="container-fluid">
  <div class="card shadow-lg">
    <div class="card-header bg-gradient-secondary text-white">
      <h5 class="mb-0">
        <i class="fas fa-file-invoice mr-2"></i>Detalle del Registro de Arriendo
      </h5>
    </div>

    <div class="card-body">
      <div class="row">
        <!-- Sección de Información del Cliente -->
        <div class="col-md-6 mb-4">
          <div class="card h-100 border-left-info">
            <div class="card-header bg-light">
              <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-user mr-2"></i>Información del Cliente
              </h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold text-muted small">RUT Cliente</label>
                    <p class="form-control-plaintext border-bottom pb-2">{{ $register->client_rut }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold text-muted small">Nombre Completo</label>
                    <p class="form-control-plaintext border-bottom pb-2">{{ $register->client_name }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sección de Información del Vehículo -->
        <div class="col-md-6 mb-4">
          <div class="card h-100 border-left-info">
            <div class="card-header bg-light">
              <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-car mr-2"></i>Información del Vehículo
              </h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold text-muted small">Auto</label>
                    <p class="form-control-plaintext border-bottom pb-2">
                      {{ optional($register->rentalCar->brand)->name_brand }}
                      {{ optional($register->rentalCar->model)->name_model }}
                    </p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold text-muted small">Sucursal</label>
                    <p class="form-control-plaintext border-bottom pb-2">{{ optional($register->rentalCar->branchOffice)->name_branch_offices }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Sección de Fechas -->
        <div class="col-md-6 mb-4">
          <div class="card h-100 border-left-info">
            <div class="card-header bg-light">
              <h6 class="m-0 font-weight-bold text-info">
                <i class="far fa-calendar-alt mr-2"></i>Período de Arriendo
              </h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold text-muted small">Fecha Inicio</label>
                    <p class="form-control-plaintext border-bottom pb-2">
                      <span class="">
                        {{ \Carbon\Carbon::parse($register->start_date)->format('d/m/Y') }}
                      </span>
                    </p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="font-weight-bold text-muted small">Fecha Término</label>
                    <p class="form-control-plaintext border-bottom pb-2">
                      <span class="">
                        {{ \Carbon\Carbon::parse($register->end_date)->format('d/m/Y') }}
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sección de Pago -->
        <div class="col-md-6 mb-4">
          <div class="card h-100 border-left-info">
            <div class="card-header bg-light">
              <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-dollar-sign mr-2"></i>Información de Pago
              </h6>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label class="font-weight-bold text-muted small">Monto Pagado</label>
                <p class="h5">
                  ${{ number_format($register->payment, 0, ',', '.') }}
                </p>
                <small class="text-muted">Valor total del arriendo</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-right mt-4">
        <a href="{{ route('registro-renta.index') }}" class="btn btn-secondary">
          Volver
        </a>
        <!-- <button class="btn btn-primary ml-2">
          Imprimir Contrato
        </button> -->
      </div>
    </div>
  </div>
</div>
@endsection