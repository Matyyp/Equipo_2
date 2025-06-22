@extends('tenant.layouts.admin')

@section('title', 'Registrar Mantención')
@section('page_title', 'Registrar Mantención')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0"><i class="fas fa-wrench me-2"></i>Registrar Mantención</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('maintenance.entries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Vehículo --}}
        <div class="mb-3">
          <label for="rental_car_id" class="form-label">Vehículo <span class="text-danger">*</span></label>
          <select name="rental_car_id" id="rental_car_id" class="form-select @error('rental_car_id') is-invalid @enderror" required>
            <option value="">-- Seleccionar --</option>
            @foreach($cars as $car)
              <option value="{{ $car->id }}" {{ old('rental_car_id') == $car->id ? 'selected' : '' }}>
                {{ $car->brand->name_brand ?? '-' }} {{ $car->model->name_model ?? '-' }}
              </option>

            @endforeach
          </select>
          @error('rental_car_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Tipo de mantención --}}
        <div class="mb-3">
          <label for="maintenance_type_id" class="form-label">Tipo de Mantención <span class="text-danger">*</span></label>
          <select name="maintenance_type_id" id="maintenance_type_id" class="form-select @error('maintenance_type_id') is-invalid @enderror" required>
            <option value="">-- Seleccionar --</option>
            @foreach($types as $type)
              <option value="{{ $type->id }}" {{ old('maintenance_type_id') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
              </option>
            @endforeach
          </select>
          @error('maintenance_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Kilometraje programado --}}
        <div class="mb-3">
          <label for="scheduled_km" class="form-label">Kilometraje programado</label>
          <input type="number" name="scheduled_km" id="scheduled_km" class="form-control @error('scheduled_km') is-invalid @enderror" value="{{ old('scheduled_km') }}">
          @error('scheduled_km') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Fecha programada --}}
        <div class="mb-3">
          <label for="scheduled_date" class="form-label">Fecha programada</label>
          <input type="date" name="scheduled_date" id="scheduled_date" class="form-control @error('scheduled_date') is-invalid @enderror" value="{{ old('scheduled_date') }}">
          @error('scheduled_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Imagen o factura --}}
        <div class="mb-3">
          <label for="image" class="form-label">Imagen / Factura (opcional)</label>
          <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*,application/pdf">
          @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-end">
          <a href="{{ route('maintenance.entries.index') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
