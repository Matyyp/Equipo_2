@extends('tenant.layouts.admin')

@section('title', 'Crear Regla')
@section('page_title', 'Registrar Nueva Regla')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-gavel me-2"></i>
      <h5 class="mb-0">Registrar Nueva Regla</h5>
    </div>

    <div class="card-body">
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

      <form action="{{ route('reglas.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
          <label for="name" class="form-label">Nombre de la Regla</label>
          <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group mb-3">
          <label for="description" class="form-label">Descripción</label>
          <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group mb-4">
          <label for="type_contract" class="form-label">Tipo de Contrato</label>
          <select name="type_contract" id="type_contract" class="form-select selectpicker" data-live-search="true" required>
            <option value="">Seleccione...</option>
            <option value="rent" {{ old('type_contract') == 'rent' ? 'selected' : '' }}>Renta</option>
            <option value="parking_daily" {{ old('type_contract') == 'parking_daily' ? 'selected' : '' }}>Estacionamiento Diario</option>
            <option value="parking_annual" {{ old('type_contract') == 'parking_annual' ? 'selected' : '' }}>Estacionamiento Anual</option>
          </select>
        </div>

        <!-- BOTONES -->
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('region.index') }}" class="btn btn-secondary me-2">
              <i class="fas fa-arrow-left me-1"></i> Cancelar
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Guardar
            </button>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<script>
  $(document).ready(function () {
    $('.selectpicker').selectpicker();
  });
</script>
@endpush
