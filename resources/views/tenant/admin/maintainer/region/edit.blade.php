@extends('tenant.layouts.admin')

@section('title', 'Editar Región')
@section('page_title', 'Editar Región')

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-edit me-2"></i>Editar Región
    </div>

    <form action="{{ route('region.update', $region->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="card-body">
        <div class="form-group mb-3">
          <label for="name_region">Nombre de la Región</label>
          <input
            type="text"
            name="name_region"
            id="name_region"
            class="form-control @error('name_region') is-invalid @enderror"
            value="{{ old('name_region', $region->name_region) }}"
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
          <i class="fas fa-save me-1"></i> Actualizar
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
