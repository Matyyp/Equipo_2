@extends('tenant.layouts.admin')

@section('title', 'Editar Tipo de Cuenta')
@section('page_title', 'Editar Tipo de Cuenta')

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-edit me-2"></i>
      <h5 class="mb-0">Editar Tipo de Cuenta</h5>
    </div>

    <div class="card-body">
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

      <form action="{{ route('tipo_cuenta.update', $type->id_type_account) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-4">
          <label for="name_type_account" class="form-label">Nombre del Tipo</label>
          <input type="text" name="name_type_account" id="name_type_account" class="form-control" required value="{{ old('name_type_account', $type->name_type_account) }}">
        </div>

        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('tipo_cuenta.index') }}" class="btn btn-secondary me-2">
              <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Actualizar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
