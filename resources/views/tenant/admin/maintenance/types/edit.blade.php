@extends('tenant.layouts.admin')

@section('title', 'Editar Tipo de Mantención')
@section('page_title', 'Editar Tipo de Mantención')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0"><i class="fas fa-cogs nav-icon me-2"></i> Editar Tipo de Mantención</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('maintenance.type.update', ['type' => $maintenanceType->id]) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-3">
          <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
          <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required
                 value="{{ old('name', $maintenanceType->name) }}">
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
          <label for="description" class="form-label">Descripción</label>
          <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $maintenanceType->description) }}</textarea>
          @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-end">
          <a href="{{ route('maintenance.type.index') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-primary ml-1">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

