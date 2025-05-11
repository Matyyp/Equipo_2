@extends('tenant.layouts.admin')

@section('title', 'Ingresar Negocio')
@section('page_title', 'Ingresar Nuevo Negocio')

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-building me-2"></i>
      <h5 class="mb-0">Formulario de Registro de Negocio</h5>
    </div>

    <div class="card-body">
      {{-- Validación de errores --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>Ups!</strong> Hay algunos problemas con tu entrada:
          <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Formulario --}}
      <form action="{{ route('empresa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
          <label for="name_business" class="form-label">Nombre de la Empresa</label>
          <input type="text" name="name_business" id="name_business"
                 class="form-control @error('name_business') is-invalid @enderror"
                 value="{{ old('name_business') }}" required>
          @error('name_business')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-3">
          <label for="electronic_transfer" class="form-label">Datos de Transferencia</label>
          <textarea name="electronic_transfer" id="electronic_transfer" rows="3"
                    class="form-control @error('electronic_transfer') is-invalid @enderror"
                    required>{{ old('electronic_transfer') }}</textarea>
          @error('electronic_transfer')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-4">
          <label for="logo" class="form-label">Logo</label>
          <input type="file" name="logo" id="logo" accept="image/*"
                 class="form-control @error('logo') is-invalid @enderror">
          @error('logo')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Botones --}}
        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('empresa.index') }}" class="btn btn-secondary me-2">
              <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Guardar Negocio
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
