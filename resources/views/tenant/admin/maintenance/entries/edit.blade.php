@extends('tenant.layouts.admin')

@section('title', 'Editar Mantención')
@section('page_title', 'Editar Mantención')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Editar Mantención</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('maintenance.entries.update', $entry) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Vehículo --}}
        <div class="mb-3">
          <label for="rental_car_id" class="form-label">Vehículo <span class="text-danger">*</span></label>
          <select name="rental_car_id" id="rental_car_id" class="form-control" required>
            @foreach($cars as $car)
              <option value="{{ $car->id }}" {{ $entry->rental_car_id == $car->id ? 'selected' : '' }}>
                {{ $car->brand->name_brand ?? '-' }} {{ $car->model->name_model ?? '-' }}
              </option>
            @endforeach
          </select>
        </div>


        {{-- Tipo de mantención --}}
        <div class="mb-3">
          <label for="maintenance_type_id" class="form-label">Tipo de Mantención <span class="text-danger">*</span></label>
          <select name="maintenance_type_id" id="maintenance_type_id" class="form-control" required>
            @foreach($types as $type)
              <option value="{{ $type->id }}" {{ $entry->maintenance_type_id == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Kilometraje programado --}}
        <div class="mb-3">
          <label for="scheduled_km" class="form-label">Kilometraje programado</label>
          <input type="number" name="scheduled_km" id="scheduled_km" class="form-control" value="{{ old('scheduled_km', $entry->scheduled_km) }}">
        </div>

        {{-- Fecha programada --}}
        <div class="mb-3">
          <label for="scheduled_date" class="form-label">Fecha programada</label>
          <input type="date" name="scheduled_date" id="scheduled_date" class="form-control" value="{{ old('scheduled_date', $entry->scheduled_date) }}">
        </div>

        {{-- Completada --}}
        <div class="mb-3">
          <label for="is_completed" class="form-label">¿Completada?</label>
          <select name="is_completed" id="is_completed" class="form-control">
            <option value="0" {{ !$entry->is_completed ? 'selected' : '' }}>No</option>
            <option value="1" {{ $entry->is_completed ? 'selected' : '' }}>Sí</option>
          </select>
        </div>

        {{-- Empleado --}}
        <div class="mb-3">
          <label for="employee_name" class="form-label">Nombre del mecánico o empleado</label>
          <input type="text" name="employee_name" id="employee_name" class="form-control" value="{{ old('employee_name', $entry->employee_name) }}">
        </div>

        {{-- Fecha finalización --}}
        <div class="mb-3">
          <label for="completed_date" class="form-label">Fecha de finalización</label>
          <input type="date" name="completed_date" id="completed_date" class="form-control" value="{{ old('completed_date', $entry->completed_date) }}">
        </div>

        {{-- Lugar --}}
        <div class="mb-3">
          <label for="location" class="form-label">Lugar de mantención</label>
          <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $entry->location) }}">
        </div>

        {{-- Número de factura --}}
        <div class="mb-3">
          <label for="invoice_number" class="form-label">Número de factura</label>
          <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ old('invoice_number', $entry->invoice_number) }}">
        </div>

        {{-- Imagen actual --}}
        @if($entry->images->first())
        <div class="mb-3">
          <label class="form-label">Factura actual (marque para eliminar)</label>
          <div class="text-center mb-2">
            <div class="border rounded overflow-hidden mb-1" style="width:100px; height:70px;">
              <img src="{{ tenant_asset($entry->images->first()->image_path) }}" alt="Factura" class="w-100 h-100 object-cover" onerror="this.onerror=null;this.src='https://via.placeholder.com/100x70?text=No+Imagen';">
            </div>
            <div class="form-check">
              <input type="checkbox" name="delete_invoice_file" id="delete_invoice_file" class="form-check-input" value="1">
              <label for="delete_invoice_file" class="form-check-label small">Eliminar factura actual</label>
            </div>
          </div>
        </div>
        @endif

        {{-- Nueva imagen --}}
        <div class="mb-3">
          <label for="invoice_file" class="form-label">Subir nueva imagen de factura</label>
          <input type="file" name="invoice_file" id="invoice_file" class="form-control" accept="image/*">
        </div>

        <div class="d-flex justify-content-end">
          <a href="{{ route('maintenance.entries.index') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-primary ml-1">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
