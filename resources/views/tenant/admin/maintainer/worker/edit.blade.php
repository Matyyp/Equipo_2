@extends('tenant.layouts.admin')

@section('title', 'Editar usuario')
@section('page_title', 'Editar usuario')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-user-edit mr-2"></i> Editar Usuario</div>
    </div>
    
    <div class="card-body">
      <form action="{{ route('trabajadores.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="name" class="form-label">Nombre</label>
          <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $user->name) }}" required>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email) }}" required>
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-4">
          <label for="role" class="form-label">Rol</label>
          <select name="role" id="role" class="form-select selectpicker @error('role') is-invalid @enderror"
            data-live-search="true" required>
            <option value="" disabled>-- Selecciona un rol --</option>
            @foreach(\Spatie\Permission\Models\Role::pluck('name') as $roleName)
              <option value="{{ $roleName }}"
                {{ old('role', $user->roles->first()?->name) === $roleName ? 'selected' : '' }}>
                {{ $roleName }}
              </option>
            @endforeach
          </select>
          @error('role') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-4">
          <label class="form-label">Sucursal</label>
          <input type="text" class="form-control" value="{{ $user->branch_office->name_branch_offices ?? 'Sin sucursal' }}" disabled>
          <input type="hidden" name="id_branch_office" value="{{ $user->id_branch_office }}">
        </div>

        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('trabajadores.show', $user->id_branch_office) }}" class="btn btn-secondary mr-1">
              Cancelar
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              Actualizar
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
