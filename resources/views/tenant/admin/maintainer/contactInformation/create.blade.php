@extends('tenant.layouts.admin')

@section('title', 'Registrar Contacto')
@section('page_title', 'Registrar Nuevo Contacto')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-address-book mr-2"></i>Contactos Registrados</div>
    </div>

    <div class="card-body">
      {{-- Validación de errores --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>Ups!</strong> Corrige los siguientes errores:
          <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Formulario --}}
      <form action="{{ route('informacion_contacto.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_branch_office" value="{{ $branchId }}">

        <div class="form-group mb-3">
          <label for="type_contact" class="form-label">Tipo de Contacto</label>
          <select name="type_contact" id="type_contact" class="form-select selectpicker" data-live-search="true" required>
            <option value="">Seleccione...</option>
            <option value="email" {{ old('type_contact') == 'email' ? 'selected' : '' }}>Correo Electrónico</option>
            <option value="phone" {{ old('type_contact') == 'phone' ? 'selected' : '' }}>Teléfono</option>
            <option value="mobile" {{ old('type_contact') == 'mobile' ? 'selected' : '' }}>Celular</option>
            <option value="whatsapp" {{ old('type_contact') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
            <option value="website" {{ old('type_contact') == 'website' ? 'selected' : '' }}>Sitio Web</option>
            <option value="social" {{ old('type_contact') == 'social' ? 'selected' : '' }}>Red Social</option>
          </select>
        </div>

        <div class="form-group mb-4">
          <label for="data_contact" class="form-label">Dato de Contacto</label>
          <input type="text" name="data_contact" id="data_contact" class="form-control" value="{{ old('data_contact') }}" required>
        </div>

        <!-- BOTONES -->
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('informacion_contacto.show', $branchId) }}" class="btn btn-secondary mr-1">
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<script>
  $(document).ready(function () {
    $('.selectpicker').selectpicker();
  });
</script>
@endpush
