@extends('tenant.layouts.admin')

@section('title', 'Crear Región')
@section('page_title', 'Registrar Nueva Región')

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-map-marked-alt me-2"></i>Nueva Región
    </div>

    <form action="{{ route('region.store') }}" method="POST">
      @csrf
      <div class="card-body">
        <div class="form-group mb-3">
          <label for="name_region">Nombre de la Región</label>
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

      <div class="card-footer text-end">
        <a href="{{ route('region.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
