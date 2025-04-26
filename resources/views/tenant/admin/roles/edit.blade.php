@extends('tenant.layouts.admin')
@section('title',"Editar permisos: {$role->name}")
@section('page_title',"Editar permisos: {$role->name}")

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Permisos del rol <strong>{{ $role->name }}</strong></h3>
    <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm float-right">
      <i class="fas fa-arrow-left"></i> Volver
    </a>
  </div>
  <div class="card-body">
    <form action="{{ route('roles.update', $role) }}" method="POST">
      @csrf @method('PUT')

      <div class="row">
        @foreach($permissions as $perm)
          <div class="col-md-4">
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

      <div class="mt-4">
        <button type="submit" class="btn btn-success">
          <i class="fas fa-save"></i> Guardar cambios
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
