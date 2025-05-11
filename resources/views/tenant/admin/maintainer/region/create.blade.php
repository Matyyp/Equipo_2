@extends('tenant.layouts.admin')

@section('title', 'Crear Región')
@section('page_title', 'Registrar Nueva Región')

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-map-marked-alt me-2"></i>
      <h5 class="mb-0">Registrar Nueva Región</h5>
    </div>

    <form action="{{ route('region.store') }}" method="POST">
      @csrf

      <div class="card-body">
        <div class="form-group mb-4">
          <label for="name_region" class="form-label">Nombre de la Región</label>
          <input
            type="text"
            name="name_region"
            id="name_region"
            class="form-control @error('name_region') is-invalid @enderror"
            value="{{ old('name_region') }}"
            placeholder="Ej. Región Metropolitana"
            required
          >
          @error('name_region')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="card-footer">
        <div class="form-group row justify-content-end mb-0">
          <div class="col-auto">
            <a href="{{ route('region.index') }}" class="btn btn-secondary me-2">
              <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Guardar
            </button>
          </div>
        </div>
      </div>

    </form>
  </div>
</div>
@endsection
