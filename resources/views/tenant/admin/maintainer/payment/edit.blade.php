<!-- edit view -->
@extends('tenant.layouts.admin')

@section('title', 'Editar Pago')
@section('page_title', 'Editar Pago')

@section('content')
<div class="container-fluid">
  <form method="POST" action="{{ route('payment.update', $payment->id_payment) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Servicio</label>
      <input type="text"
             class="form-control"
             value="{{ $payment->service->name }}"
             readonly>
    </div>

    <div class="mb-3">
      <label class="form-label">Monto</label>
      <input type="number" step="0.01"
             name="amount"
             class="form-control @error('amount') is-invalid @enderror"
             value="{{ old('amount', $payment->amount) }}"
             required>
      @error('amount')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Método de Pago</label>
      <select name="type_payment"
              class="form-control @error('type_payment') is-invalid @enderror"
              required>
        <option value="">-- Selecciona --</option>
        @foreach(['efectivo','tarjeta','transferencia'] as $method)
          <option value="{{ $method }}"
            {{ old('type_payment', $payment->type_payment) == $method ? 'selected' : '' }}>
            {{ ucfirst($method) }}
          </option>
        @endforeach
      </select>
      @error('type_payment')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Fecha de Pago</label>
      <input type="date"
             name="payment_date"
             class="form-control @error('payment_date') is-invalid @enderror"
             value="{{ old('payment_date', $payment->payment_date->toDateString()) }}"
             required>
      @error('payment_date')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
    <a href="{{ route('payment.index') }}" class="btn btn-secondary mr-1">Cancelar</a>
    <button type="submit" class="btn btn-success">Actualizar</button>
    
  </form>
</div>
@endsection
