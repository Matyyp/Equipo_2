{{-- resources/views/tenant/admin/maintainer/payment/create.blade.php --}}

@extends('tenant.layouts.admin')

@section('title', 'Registrar Pago')
@section('page_title', 'Registrar Nuevo Pago')

@section('content')
<div class="container mt-5">
  <h3>Servicios disponibles</h3>

  <table class="table table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Tipo</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
      @foreach($services as $s)
        <tr>
          <td>{{ $s->id_service }}</td>
          <td>{{ $s->name }}</td>
          <td>${{ number_format($s->price_net, 0, ',', '.') }}</td>
          <td>{{ ucfirst(str_replace('_',' ', $s->type_service)) }}</td>
          <td>
            <button
              type="button"
              class="btn btn-sm btn-primary"
              data-toggle="modal"
              data-target="#paymentModal"
              data-id="{{ $s->id_service }}"
              data-name="{{ $s->name }}"
              data-price="{{ $s->price_net }}">
              Pagar
            </button>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ route('payment.store') }}">
      @csrf
      {{-- Hidden inputs obligatorios --}}
      <input type="hidden" name="id_service" id="modalServiceId">
      <input type="hidden" name="id_voucher" id="modalVoucherId">
    
      <div class="modal-content">
        <div class="modal-header">…</div>
        <div class="modal-body">
          {{-- Servicio --}}
          <div class="form-group">
            <label>Servicio</label>
            <input type="text" id="modalServiceName" class="form-control" readonly>
          </div>
    
          {{-- Voucher (solo display) --}}
          <div class="form-group">
            <label>Voucher</label>
            <input type="text" id="modalVoucherIdDisplay" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Total</label>
            <input type="text" name="amount" id="modalAmount" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Método de Pago</label>
            <select name="type_payment" id="modalTypePayment" class="form-control" required>
              <option value="">-- Selecciona --</option>
              <option value="efectivo">Efectivo</option>
              <option value="tarjeta">Tarjeta</option>
              <option value="transferencia">Transferencia</option>
            </select>
          </div>

          <div class="form-group">
            <label>Fecha de Pago</label>
            <input
              type="date"
              name="payment_date"
              id="modalPaymentDate"
              class="form-control"
              value="{{ now()->toDateString() }}"
              required
            >
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-success">
            Guardar Pago
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $('#paymentModal').on('show.bs.modal', function (e) {
    const btn = $(e.relatedTarget);
    const svc = btn.data('id');
    const voucherId = svc; // o la lógica que necesites

    $('#modalServiceId').val(svc);
    $('#modalServiceName').val(btn.data('name'));

    $('#modalVoucherId').val(voucherId);
    $('#modalVoucherIdDisplay').val(voucherId);

    $('#modalAmount').val(btn.data('price'));
    $('#modalTypePayment').val('');
    $('#modalPaymentDate').val(new Date().toISOString().slice(0,10));
  });
</script>
@endpush
