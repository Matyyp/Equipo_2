@extends('tenant.layouts.admin')

@section('title', 'Editar Propietario')
@section('page_title', 'Editar Información del Propietario')

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-user-edit me-2"></i>
      <h5 class="mb-0">Editar Propietario</h5>
    </div>

    <div class="card-body">
      {{-- Validación de errores --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>Ups!</strong> Revisa los siguientes errores:
          <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Formulario --}}
      <form action="{{ route('dueños.update', $owner->id_owner) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="type_owner">Tipo de Propietario</label>
          <select name="type_owner" id="type_owner" class="form-select form-control" required>
            <option value="cliente" {{ old('type_owner', $owner->type_owner) == 'cliente' ? 'selected' : '' }}>Cliente</option>
            <option value="empresa" {{ old('type_owner', $owner->type_owner) == 'empresa' ? 'selected' : '' }}>Empresa</option>
          </select>
        </div>

        <div class="form-group mb-3">
          <label for="name">Nombre</label>
          <input type="text" name="name" id="name" class="form-control"
                 value="{{ old('name', $owner->name) }}" required>
        </div>

        <div class="form-group mb-4">
          <label for="number_phone">Número de Teléfono</label>
          <input type="text" name="number_phone" id="number_phone" class="form-control"
                 value="{{ old('number_phone', $owner->number_phone) }}" required>
        </div>
        
        <div class="form-group row justify-content-end mt-4">
        <div class="col-auto">
            <a href="{{ route('dueños.index') }}" class="btn btn-secondary" style="margin-right: 12px;">
            <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success" id="submit-btn">
            <i class="fas fa-plus mr-1"></i> Asociar Vehículos Seleccionados
            </button>
        </div>
        </div>


      </form>
    </div>
  </div>
</div>
@endsection
