@extends('tenant.layouts.admin')

@section('title', 'Nuevo Tipo de Cuenta')
@section('page_title', 'Registrar Tipo de Cuenta')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-wallet mr-2"></i> Registrar Tipo de Cuenta</div>
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

      <form action="{{ route('tipo_cuenta.store') }}" method="POST">
        @csrf

        <div class="form-group mb-4">
          <label for="name_type_account" class="form-label">Nombre del Tipo</label>
          <input
            type="text"
            name="name_type_account"
            id="name_type_account"
            class="form-control"
            required
            value="{{ old('name_type_account') }}"
          >
        </div>

        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('tipo_cuenta.index') }}" class="btn btn-secondary me-1">
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
