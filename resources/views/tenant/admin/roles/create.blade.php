@extends('tenant.layouts.admin')

@section('title', 'Nuevo rol')
@section('page_title', 'Crear rol')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div>
        <i class="fas fa-user-shield me-2"></i> Nuevo Rol 
      </div>
    </div>

    <div class="card-body">
      <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        {{-- Nombre del rol --}}
        <div class="form-group mb-4">
          <label for="name" class="form-label">Nombre del rol</label>
          <input 
            type="text" 
            name="name" 
            id="name" 
            class="form-control @error('name') is-invalid @enderror" 
            value="{{ old('name') }}" 
            required
          >
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Permisos --}}
        <div class="form-group mb-4">
          <label class="form-label">Permisos</label>
          <div class="row">
            @foreach($permissions as $perm)
              <div class="col-md-4 mb-2">
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

        {{-- Botones --}}
        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary mr-1">
              Cancelar
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-success">
              Guardar
            </button>
          </div>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection
