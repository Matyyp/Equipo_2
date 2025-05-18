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
                    value="{{ $perm->name }}"
                    id="perm_{{ $perm->id }}"
                    {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}
                  >
                  <label class="form-check-label" for="perm_{{ $perm->id }}">
                    {{ $perm->name }}
                  </label>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary mr-2">
              <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save me-1"></i> Guardar cambios
            </button>
          </div>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection
