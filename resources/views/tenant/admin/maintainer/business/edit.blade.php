@extends('tenant.layouts.admin')

@section('title', 'Editar Negocio')
@section('page_title', 'Editar Información del Negocio')

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-edit me-2"></i>
      <h5 class="mb-0">Formulario de Edición</h5>
    </div>

    <div class="card-body">
      {{-- Validación de errores --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>Ups!</strong> Hubo algunos errores con tus datos:
          <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('empresa.update', $business->id_business) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="name_business" class="form-label">Nombre de la Empresa</label>
          <input type="text" id="name_business" name="name_business"
                 class="form-control @error('name_business') is-invalid @enderror"
                 value="{{ old('name_business', $business->name_business) }}" required>
          @error('name_business')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-3">
          <label for="electronic_transfer" class="form-label">Datos de Transferencia</label>
          <input type="text" id="electronic_transfer" name="electronic_transfer"
                 class="form-control @error('electronic_transfer') is-invalid @enderror"
                 value="{{ old('electronic_transfer', $business->electronic_transfer) }}" required>
          @error('electronic_transfer')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-4">
          <label for="logo" class="form-label">Logo (opcional)</label>
          <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
          @if ($business->logo)
            <p class="mt-3 mb-1">Logo actual:</p>
            <img src="/storage/tenants/{{ request()->getHost() }}/imagenes/{{ $business->logo }}"
                 alt="Logo del Negocio" class="img-thumbnail" width="100">
          @endif
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
              <i class="fas fa-save me-1"></i> Guardar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
