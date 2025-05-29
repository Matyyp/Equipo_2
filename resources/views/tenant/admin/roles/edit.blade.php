@extends('tenant.layouts.admin')

@section('title', "Editar permisos: {$role->name}")
@section('page_title', "Editar permisos: {$role->name}")

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div>
        <i class="fas fa-user-cog me-2"></i> Permisos del Rol: <strong>{{ $role->name }}</strong>
      </div>
    </div>

    <div class="card-body">
      <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Permisos --}}
        <div class="form-group mb-4">
          <label class="form-label fw-bold text-dark fs-5">Permisos</label>

          @foreach($groupedPermissions as $group => $perms)
            <div class="mb-4 p-3 border rounded shadow-sm bg-light">
              <h6 class="text-info fw-bold mb-3">
                <i class="fas fa-folder me-1"></i> {{ $group }}
              </h6>

              <div class="row">
                @foreach($perms as $permName => $label)
                  <div class="col-md-4 mb-2">
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        name="permissions[]"
                        id="perm_{{ Str::slug($permName) }}"
                        value="{{ $permName }}"
                        {{ in_array($permName, old('permissions', $rolePermissions ?? [])) ? 'checked' : '' }}
                      >
                      <label class="form-check-label" for="perm_{{ Str::slug($permName) }}">
                        {{ $label }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endforeach

          @error('permissions')
            <div class="text-danger mt-2">{{ $message }}</div>
          @enderror
        </div>

        {{-- Botones --}}
        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
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
