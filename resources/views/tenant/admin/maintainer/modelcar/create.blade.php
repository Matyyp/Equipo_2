@extends('tenant.layouts.admin')

@section('title', 'Registrar Modelo')
@section('page_title', 'Registrar Nuevo Modelo')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-car-side mr-2"></i> Registrar Nuevo Modelo</div>
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
      <form action="{{ route('modelo.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
          <label for="name_model" class="form-label">Nombre del Modelo</label>
          <input type="text" name="name_model" id="name_model" class="form-control"
                 value="{{ old('name_model') }}" required>
        </div>

        <!-- BOTONES -->
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('modelo.index') }}" class="btn btn-secondary mr-1">
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
