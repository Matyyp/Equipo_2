@extends('tenant.layouts.admin')

@section('title', 'Crear Ubicación')
@section('page_title', 'Ingresar Nueva Ubicación')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-map-location mr-2"></i> Registro de una comuna</div>
    </div>

    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>¡Ups!</strong> Corrige los siguientes errores:
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('locacion.store') }}" method="POST">
        @csrf

        {{-- Región --}}
        <div class="form-group mb-3">
          <label for="region" class="form-label">Región</label>
          <select name="region" id="region" class="form-select selectpicker @error('region') is-invalid @enderror" data-live-search="true" required>
            <option value="">Seleccione una región</option>
            @foreach($region as $r)
              <option value="{{ $r->id }}" {{ old('region') == $r->id ? 'selected' : '' }}>
                {{ $r->name_region }}
              </option>
            @endforeach
          </select>
          @error('region')
            <span class="invalid-feedback d-block">{{ $message }}</span>
          @enderror
        </div>

        {{-- Comuna --}}
        <div class="form-group mb-4">
          <label for="commune" class="form-label">Comuna</label>
          <input
            type="text"
            name="commune"
            id="commune"
            class="form-control @error('commune') is-invalid @enderror"
            value="{{ old('commune') }}"
            required
          >
          @error('commune')
            <span class="invalid-feedback d-block">{{ $message }}</span>
          @enderror
        </div>

        {{-- Botones --}}
        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('locacion.index') }}" class="btn btn-secondary me-1">
              Cancelar
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              Guardar 
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
