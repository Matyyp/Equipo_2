@extends('tenant.layouts.admin')

@section('title', 'Editar Cuenta Bancaria')
@section('page_title', 'Editar Cuenta Bancaria')

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-edit me-2"></i> Editar Cuenta
    </div>
    <div class="card-body">
      <form action="{{ route('cuentas_bancarias.update', $detail->id_bank_details) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="name" class="form-label">Titular</label>
          <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $detail->name) }}">
        </div>

        <div class="mb-3">
          <label for="rut" class="form-label">RUT</label>
          <input type="text" name="rut" id="rut" class="form-control" required value="{{ old('rut', $detail->rut) }}">
        </div>

        <div class="mb-3">
          <label for="account_number" class="form-label">Número de Cuenta</label>
          <input type="text" name="account_number" id="account_number" class="form-control" required value="{{ old('account_number', $detail->account_number) }}">
        </div>

        <div class="mb-3">
          <label for="id_bank" class="form-label">Banco</label>
          <select name="id_bank" id="id_bank" class="form-control" required>
            <option value="">Seleccione un banco</option>
            @foreach($banks as $bank)
              <option value="{{ $bank->id_bank }}" {{ old('id_bank', $detail->id_bank) == $bank->id_bank ? 'selected' : '' }}>
                {{ $bank->name_bank }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label for="id_type_account" class="form-label">Tipo de Cuenta</label>
          <select name="id_type_account" id="id_type_account" class="form-control" required>
            <option value="">Seleccione un tipo</option>
            @foreach($types as $type)
              <option value="{{ $type->id_type_account }}" {{ old('id_type_account', $detail->id_type_account) == $type->id_type_account ? 'selected' : '' }}>
                {{ $type->name_type_account }}
              </option>
            @endforeach
          </select>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Actualizar
        </button>
        <a href="{{ route('cuentas_bancarias.index') }}" class="btn btn-secondary">Cancelar</a>
      </form>
    </div>
  </div>
</div>
@endsection
