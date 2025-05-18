@extends('tenant.layouts.admin')

@section('title', 'Editar Banco')
@section('page_title', 'Editar Banco')

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-university mr-2"></i> Editar Banco</div>
    </div>

    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>Ups!</strong> Por favor corrige los siguientes errores:
          <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('banco.update', $bank->id_bank) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-4">
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

        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('banco.index') }}" class="btn btn-secondary me-2">
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
