@extends('tenant.layouts.admin')

@section('title', 'Nuevo rol')
@section('page_title', 'Crear rol')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Nuevo Rol</h3>
    <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm float-right">
      <i class="fas fa-arrow-left"></i> Volver
    </a>
  </div>
  <div class="card-body">
    <form action="{{ route('roles.store') }}" method="POST">
      @csrf

      {{-- Nombre del rol --}}
      <div class="form-group">
        <label for="name">Nombre del rol</label>
        <input 
          type="text" 
          name="name" 
          id="name" 
          class="form-control @error('name') is-invalid @enderror" 
          value="{{ old('name') }}" 
          required
        >
        @error('name')
          <span class="invalid-feedback">{{ $message }}</span>
        @enderror
      </div>

      {{-- Permisos --}}
      <div class="form-group">
        <label>Permisos</label>
        <div class="row">
          @foreach($permissions as $perm)
            <div class="col-md-4">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="permissions[]"
                  id="perm_{{ $perm->id }}"
                  value="{{ $perm->name }}"
                  {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="perm_{{ $perm->id }}">
                  {{ $perm->name }}
                </label>
              </div>
            </div>
          @endforeach
        </div>
        @error('permissions')
          <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      {{-- Botón --}}
      <div class="mt-4">
        <button type="submit" class="btn btn-success">
          <i class="fas fa-save"></i> Crear rol
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
