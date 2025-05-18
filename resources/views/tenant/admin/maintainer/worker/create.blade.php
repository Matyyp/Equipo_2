@extends('tenant.layouts.admin')

@section('title', 'Nuevo usuario')
@section('page_title')
  Nuevo usuario 
  @if(isset($sucursal))
    <small class="text-muted d-block">Sucursal: {{ $sucursal->name_branch_offices }}</small>
  @endif
@endsection


@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div>
        <i class="fas fa-user-plus mr-2"></i> Crear Nuevo Usuario 
        @if(isset($sucursal))
          <span class="text-light">en la <strong>{{ $sucursal->name_branch_offices }}</strong></span>
        @endif
      </div>
    </div>

    <div class="card-body">
      <form action="{{ route('trabajadores.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
          <label for="name" class="form-label">Nombre</label>
          <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
          <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
          <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group mb-4">
          <label for="role" class="form-label">Rol</label>
          <select name="role" id="role" class="form-select selectpicker @error('role') is-invalid @enderror" data-live-search="true" required>
            <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Selecciona un rol --</option>
            @foreach(\Spatie\Permission\Models\Role::pluck('name') as $roleName)
              <option value="{{ $roleName }}" {{ old('role') === $roleName ? 'selected' : '' }}>{{ $roleName }}</option>
            @endforeach
          </select>
          @error('role') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        @if(isset($sucursal))
          <div class="form-group mb-4">
            <label class="form-label">Sucursal</label>
            <input type="text" class="form-control" value="{{ $sucursal->name_branch_offices }}" disabled>
            <input type="hidden" name="id_branch_office" value="{{ $sucursal->id_branch }}">
          </div>
        @endif


        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('trabajadores.show', $sucursal->id_branch) }}" class="btn btn-secondary mr-2">
              <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Guardar
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
