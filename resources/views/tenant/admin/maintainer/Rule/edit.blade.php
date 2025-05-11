@extends('tenant.layouts.admin')

@section('title', 'Editar Regla')
@section('page_title', 'Editar Regla del Sistema')

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-edit me-2"></i>
      <h5 class="mb-0">Editar Regla del Sistema</h5>
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

      {{-- Lógica para traducir tipo de contrato --}}
      @php
        $tipoContrato = match($rule->type_contract) {
          'rent' => 'Renta',
          'parking_daily' => 'Estacionamiento Diario',
          'parking_annual' => 'Estacionamiento Anual',
          default => 'Desconocido',
        };
      @endphp

      <form action="{{ route('reglas.update', $rule->id_rule) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="name" class="form-label">Nombre de la Regla</label>
          <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $rule->name) }}" required>
        </div>

        <div class="form-group mb-3">
          <label for="description" class="form-label">Descripción</label>
          <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description', $rule->description) }}</textarea>
        </div>

        <div class="form-group mb-4">
          <label class="form-label">Tipo de Contrato Asociado</label>
          <input type="text" class="form-control bg-light" readonly value="{{ $tipoContrato }}">
        </div>

        <!-- BOTONES -->
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('reglas.index') }}" class="btn btn-secondary mr-2">
              <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save mr-1"></i> Actualizar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
