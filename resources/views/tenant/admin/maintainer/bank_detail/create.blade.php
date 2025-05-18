@extends('tenant.layouts.admin')

@section('title', 'Registrar Cuenta Bancaria')
@section('page_title', 'Nueva Cuenta Bancaria')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-bank mr-2"></i> Registrar Cuenta Bancaria</div>
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

      <form action="{{ route('cuentas_bancarias.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
          <label for="name" class="form-label">Titular</label>
          <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="form-group mb-3">
          <label for="rut" class="form-label">RUT</label>
          <input type="text" name="rut" id="rut" class="form-control" required value="{{ old('rut') }}">
        </div>

        <div class="form-group mb-3">
          <label for="account_number" class="form-label">Número de Cuenta</label>
          <input type="text" name="account_number" id="account_number" class="form-control" required value="{{ old('account_number') }}">
        </div>

        <div class="form-group mb-3">
          <label for="id_bank" class="form-label">Banco</label>
          <select name="id_bank" id="id_bank" class="form-select selectpicker" data-live-search="true" required>
            <option value="">Seleccione un banco</option>
            @foreach($banks as $bank)
              <option value="{{ $bank->id_bank }}" {{ old('id_bank') == $bank->id_bank ? 'selected' : '' }}>
                {{ $bank->name_bank }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group mb-4">
          <label for="id_type_account" class="form-label">Tipo de Cuenta</label>
          <select name="id_type_account" id="id_type_account" class="form-select selectpicker" data-live-search="true" required>
            <option value="">Seleccione un tipo</option>
            @foreach($types as $type)
              <option value="{{ $type->id_type_account }}" {{ old('id_type_account') == $type->id_type_account ? 'selected' : '' }}>
                {{ $type->name_type_account }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('cuentas_bancarias.index') }}" class="btn btn-secondary me-2">
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<script>
  $(document).ready(function () {
    $('.selectpicker').selectpicker();
  });
</script>
@endpush
