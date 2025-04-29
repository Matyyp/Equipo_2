{{-- resources/views/tenant/admin/parking/parking_edit.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Editar Ingreso al Estacionamiento')
@section('page_title', 'Editar Ingreso al Estacionamiento')

@push('styles')
  <!-- bootstrap-select CSS -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css"
  />
@endpush

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-warning text-dark">
      <i class="fas fa-edit me-2"></i>Editar Ingreso
    </div>
    <div class="card-body">
      <form action="{{ route('estacionamiento.update', $parking->id_parking_register) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Patente --}}
        <div class="mb-3">
          <label for="plate" class="form-label">Patente</label>
          <input
            type="text"
            id="plate"
            name="plate"
            class="form-control @error('plate') is-invalid @enderror"
            value="{{ old('plate', $car->patent) }}"
            maxlength="8"
            required
          >
          @error('plate')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Nombre / Teléfono --}}
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Nombre</label>
            <input
              type="text"
              id="name"
              name="name"
              class="form-control @error('name') is-invalid @enderror"
              value="{{ old('name', $owner->name) }}"
              required
            >
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="phone" class="form-label">Teléfono</label>
            <input
              type="tel"
              id="phone"
              name="phone"
              class="form-control @error('phone') is-invalid @enderror"
              value="{{ old('phone', $owner->number_phone) }}"
              required
            >
            @error('phone')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Fechas --}}
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="start_date" class="form-label">Fecha de Inicio</label>
            <input
              type="date"
              id="start_date"
              name="start_date"
              class="form-control @error('start_date') is-invalid @enderror"
              value="{{ old('start_date', $parking->start_date) }}"
              required
            >
            @error('start_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="end_date" class="form-label">Fecha de Término</label>
            <input
              type="date"
              id="end_date"
              name="end_date"
              class="form-control @error('end_date') is-invalid @enderror"
              value="{{ old('end_date', $parking->end_date) }}"
              required
            >
            @error('end_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Kilometraje --}}
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="arrival_km" class="form-label">Km Entrada</label>
            <input
              type="number"
              id="arrival_km"
              name="arrival_km"
              class="form-control @error('arrival_km') is-invalid @enderror"
              value="{{ old('arrival_km', $parking->arrival_km) }}"
              min="0"
            >
            @error('arrival_km')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="km_exit" class="form-label">Km Salida</label>
            <input
              type="number"
              id="km_exit"
              name="km_exit"
              class="form-control @error('km_exit') is-invalid @enderror"
              value="{{ old('km_exit', $parking->km_exit) }}"
              min="0"
            >
            @error('km_exit')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Tipo de Estacionamiento --}}
        <div class="mb-3">
          <label for="service_id" class="form-label">Tipo de Estacionamiento</label>
          <select
            id="service_id"
            name="service_id"
            class="selectpicker form-control @error('service_id') is-invalid @enderror"
            data-live-search="true"
            title="Seleccione un tipo"
            required
          >
            @foreach($parkingServices as $svc)
              <option
                value="{{ $svc->id_service }}"
                @selected(old('service_id', $service->id_service) == $svc->id_service)
              >{{ $svc->name }} — ${{ number_format($svc->price_net, 0, '.', ',') }}</option>
            @endforeach
          </select>
          @error('service_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Marca / Modelo --}}
        <div class="row mb-4">
          <div class="col-md-6">
            <label for="id_brand" class="form-label">Marca</label>
            <select
              id="id_brand"
              name="id_brand"
              class="selectpicker form-control @error('id_brand') is-invalid @enderror"
              data-live-search="true"
              title="Seleccione una marca"
              required
            >
              @foreach($brands as $b)
                <option
                  value="{{ $b->id_brand }}"
                  @selected(old('id_brand', $car->id_brand) == $b->id_brand)
                >{{ $b->name_brand }}</option>
              @endforeach
            </select>
            @error('id_brand')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="id_model" class="form-label">Modelo</label>
            <select
              id="id_model"
              name="id_model"
              class="selectpicker form-control @error('id_model') is-invalid @enderror"
              data-live-search="true"
              title="Seleccione un modelo"
              required
            >
              @foreach($models as $m)
                <option
                  value="{{ $m->id_model }}"
                  @selected(old('id_model', $car->id_model) == $m->id_model)
                >{{ $m->name_model }}</option>
              @endforeach
            </select>
            @error('id_model')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Lavado --}}
        <div class="form-check mb-4">
          <input
            type="checkbox"
            id="wash_service"
            name="wash_service"
            class="form-check-input"
            @checked(old('wash_service', $parking->wash_service))
          >
          <label for="wash_service" class="form-check-label">Incluye Lavado</label>
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-end">
          <a href="{{ route('estacionamiento.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i> Cancelar
          </a>
          <button type="submit" class="btn btn-warning">
            <i class="fas fa-save me-1"></i> Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <!-- bootstrap-select JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    $('.selectpicker').selectpicker();
  });
  </script>
@endpush
