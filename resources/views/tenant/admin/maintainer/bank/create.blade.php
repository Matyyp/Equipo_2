@extends('tenant.layouts.admin')

@section('title', 'Registrar Banco')
@section('page_title', 'Registrar Nuevo Banco')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-university mr-2"></i> Registrar Nuevo Banco</div>
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

      <form action="{{ route('banco.store') }}" method="POST">
        @csrf

        <div class="form-group mb-4">
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

        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('banco.index') }}" class="btn btn-secondary me-1">
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
