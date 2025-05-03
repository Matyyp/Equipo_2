@extends('tenant.layouts.admin')

@section('title', 'Nuevo Tipo de Cuenta')
@section('page_title', 'Registrar Tipo de Cuenta')

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-plus-circle me-2"></i> Nuevo Tipo de Cuenta
    </div>
    <div class="card-body">
      <form action="{{ route('tipo_cuenta.store') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="name_type_account" class="form-label">Nombre del Tipo</label>
          <input type="text" name="name_type_account" id="name_type_account" class="form-control" required value="{{ old('name_type_account') }}">
        </div>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Guardar
        </button>
        <a href="{{ route('tipo_cuenta.index') }}" class="btn btn-secondary">Cancelar</a>
      </form>
    </div>
  </div>
</div>
@endsection
