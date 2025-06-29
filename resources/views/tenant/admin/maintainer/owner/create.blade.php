@extends('tenant.layouts.admin')

@section('title', 'Registrar Propietario')
@section('page_title', 'Registrar Nuevo Propietario')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-user mr-2"></i> Registrar Nuevo Propietario</div>
    </div>

    <div class="card-body">
      {{-- Validación de errores --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>Ups!</strong> Por favor revisa los siguientes errores:
          <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Formulario --}}
      <form action="{{ route('dueños.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
          <label for="name">Nombre</label>
          <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group mb-4">
          <label for="number_phone">Número de Teléfono</label>
          <input type="text" name="number_phone" id="number_phone" class="form-control" value="{{ old('number_phone') }}" required>
        </div>

        <!-- BOTONES -->
        <div class="form-group row justify-content-end mt-4">
        <div class="col-auto">
            <a href="{{ route('dueños.index') }}" class="btn btn-secondary mr-1">
            Cancelar
            </a>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary" id="submit-btn">
            Guardar
            </button>
        </div>
        </div>



      </form>
    </div>
  </div>
</div>
@endsection
