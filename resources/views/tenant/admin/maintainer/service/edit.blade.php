@extends('tenant.layouts.admin')

@section('title', 'Editar Servicio')
@section('page_title', 'Editar Información del Servicio')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-pen me-2"></i>Editar Servicio
    </div>
    <div class="card-body">
      {{-- Validación de errores --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>Ups!</strong> Revisa los siguientes errores:
          <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('servicios.update', $service->id_service) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="name" class="form-label">Nombre del Servicio</label>
          <input type="text" name="name" id="name" class="form-control"
                 value="{{ old('name', $service->name) }}" readonly>
        </div>

        <div class="form-group mb-3">
          <label for="price_net" class="form-label">Precio</label>
          <input type="number" name="price_net" id="price_net" class="form-control"
                 value="{{ old('price_net', $service->price_net) }}" min="0" required>
        </div>

        <div class="form-group mb-4">
            <div class="d-none">
            <select id="type_service" class="form-select">
                <option value="parking_daily" {{ $service->type_service == 'parking_daily' ? 'selected' : '' }}>Estacionamiento Diario</option>
                <option value="parking_annual" {{ $service->type_service == 'parking_annual' ? 'selected' : '' }}>Estacionamiento Anual</option>
                <option value="car_wash" {{ $service->type_service == 'car_wash' ? 'selected' : '' }}>Lavado de Autos</option>
                <option value="rent" {{ $service->type_service == 'rent' ? 'selected' : '' }}>Arriendo</option>
            </select>
            </div>
            <input type="hidden" name="type_service" value="{{ $service->type_service }}">
        </div>

        <!-- BOTONES -->
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('servicios.show', $service->id_branch_office) }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary ">
              <i class="fas fa-save me-1"></i> Actualizar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
