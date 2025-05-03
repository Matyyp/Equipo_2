@extends('tenant.layouts.admin')

@section('title', 'Editar Banco')
@section('page_title', 'Editar Banco')

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-edit me-2"></i> Editar Banco
    </div>
    <div class="card-body">
      <form action="{{ route('banco.update', $bank->id_bank) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="name_bank" class="form-label">Nombre del Banco</label>
          <input
            type="text"
            name="name_bank"
            id="name_bank"
            class="form-control"
            value="{{ old('name_bank', $bank->name_bank) }}"
            required
          >
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Actualizar
        </button>
        <a href="{{ route('banco.index') }}" class="btn btn-secondary">Cancelar</a>
      </form>
    </div>
  </div>
</div>
@endsection
