@extends('tenant.layouts.admin')

@section('title', 'Editar Tipo de Cuenta')
@section('page_title', 'Editar Tipo de Cuenta')

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-edit me-2"></i> Editar Tipo de Cuenta
    </div>
    <div class="card-body">
      <form action="{{ route('tipo_cuenta.update', $type->id_type_account) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
          <label for="name_type_account" class="form-label">Nombre del Tipo</label>
          <input type="text" name="name_type_account" id="name_type_account" class="form-control" required value="{{ old('name_type_account', $type->name_type_account) }}">
        </div>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Actualizar
        </button>
        <a href="{{ route('tipo_cuenta.index') }}" class="btn btn-secondary">Cancelar</a>
      </form>
    </div>
  </div>
</div>
@endsection
