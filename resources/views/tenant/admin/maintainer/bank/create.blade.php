@extends('tenant.layouts.admin')

@section('title', 'Registrar Banco')
@section('page_title', 'Registrar Nuevo Banco')

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-plus-circle me-2"></i> Nuevo Banco
    </div>
    <div class="card-body">
      <form action="{{ route('banco.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label for="name_bank" class="form-label">Nombre del Banco</label>
          <input
            type="text"
            name="name_bank"
            id="name_bank"
            class="form-control"
            value="{{ old('name_bank') }}"
            required
          >
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Guardar
        </button>
        <a href="{{ route('banco.index') }}" class="btn btn-secondary">Cancelar</a>
      </form>
    </div>
  </div>
</div>
@endsection
