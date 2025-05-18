@extends('tenant.layouts.admin')

@section('title', 'Editar Ingreso al Estacionamiento')
@section('page_title', 'Editar Ingreso al Estacionamiento')

@push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css" />
@endpush

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div>
        <i class="fas fa-edit mr-2"></i>Editar Ingreso
      </div>
    </div>

    <div class="card-body">
      <form action="{{ route('estacionamiento.update', $parking->id_parking_register) }}" method="POST" id="edit-form">
        @csrf
        @method('PUT')

        <input type="hidden" id="original_phone" value="{{ $owner->number_phone }}">

        {{-- Patente (solo lectura) --}}
        <div class="mb-3">
          <label for="plate" class="form-label">Patente</label>
          <input type="text" id="plate" name="plate" class="form-control" value="{{ old('plate', $car->patent) }}" readonly>
        </div>

        {{-- Nombre / Teléfono --}}
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $owner->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $owner->number_phone) }}" maxlength="9" required>
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- Fechas --}}
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="start_date" class="form-label">Fecha de Inicio</label>
            <input type="date" id="start_date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $parking->start_date) }}" required>
            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label for="end_date" class="form-label">Fecha de Término</label>
            <input type="date" id="end_date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $parking->end_date) }}" required>
            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- Kilometraje --}}
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="arrival_km" class="form-label">Km Entrada</label>
            <input type="number" id="arrival_km" name="arrival_km" class="form-control" value="{{ old('arrival_km', $parking->arrival_km) }}" min="0">
          </div>
          <div class="col-md-6">
            <label for="km_exit" class="form-label">Km Salida</label>
            <input type="number" id="km_exit" name="km_exit" class="form-control" value="{{ old('km_exit', $parking->km_exit) }}" min="0">
          </div>
        </div>

        {{-- Tipo de Estacionamiento (solo lectura) --}}
        <div class="mb-3">
          <label for="service_id" class="form-label">Tipo de Estacionamiento</label>
          <input type="text" class="form-control" value="{{ $service->name }}" readonly>
        </div>

        {{-- Marca / Modelo --}}
        <div class="row mb-4">
          <div class="col-md-6">
            <label for="brand_name" class="form-label">Marca</label>
            <input type="text" id="brand_name" name="brand_name" class="form-control" value="{{ old('brand_name', $car->car_brand->name_brand ?? '') }}" required>
          </div>
          <div class="col-md-6">
            <label for="model_name" class="form-label">Modelo</label>
            <input type="text" id="model_name" name="model_name" class="form-control" value="{{ old('model_name', $car->car_model->name_model ?? '') }}" required>
          </div>
        </div>

        {{-- Lavado --}}
        <div class="form-check mb-4">
          <input type="checkbox" id="wash_service" name="wash_service" class="form-check-input" @checked(old('wash_service', $parking->wash_service))>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const phoneUrl = '{{ route("estacionamiento.searchPhone") }}';
    const phoneInput = document.getElementById('phone');
    const originalPhone = document.getElementById('original_phone').value;
    const form = document.getElementById('edit-form');

    form.addEventListener('submit', async (e) => {
      const newPhone = phoneInput.value.trim();
      if (newPhone !== originalPhone) {
        try {
          const res = await fetch(`${phoneUrl}?phone=${newPhone}`);
          const data = await res.json();
          if (data.found) {
            e.preventDefault();
            Swal.fire({
              icon: 'error',
              title: 'Número en uso',
              text: `El teléfono ya pertenece a ${data.name}.`
            });
          }
        } catch (error) {
          e.preventDefault();
          Swal.fire('Error', 'No se pudo verificar el número.', 'error');
        }
      }
    });
  });
</script>
@endpush
