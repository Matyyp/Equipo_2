@extends('tenant.layouts.admin')

@section('title', 'Crear Marca')
@section('page_title', 'Registrar Nueva Marca')

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-tag me-2"></i>
      <h5 class="mb-0">Registrar Nueva Marca</h5>
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
      <form action="{{ route('marca.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
          <label for="name_brand" class="form-label">Nombre de la Marca</label>
          <input type="text" name="name_brand" id="name_brand" class="form-control" value="{{ old('name_brand') }}" required>
        </div>

        <!-- BOTONES -->
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('marca.index') }}" class="btn btn-secondary mr-2">
              <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save mr-1"></i> Guardar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
