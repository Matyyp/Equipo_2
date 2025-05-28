{{-- resources/views/tenant/admin/maintainer/payment/create.blade.php --}}
@extends('tenant.layouts.admin')

@section('content')
<div class="container-fluid">
  <h3>Pagos Pendientes</h3>

  <table class="table table-hover">
    <thead>
      <tr>
        <th>Registro</th>
        <th>Servicio</th>
        <th>Precio</th>
        <th>Tipo</th>
        <th>Patente</th>
        <th>Dueño</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
      @foreach($registers as $r)
        @php
          $svc      = $r->service;
          $pr       = $r->parkingRegister;                   // relación belongsTo
          $park     = $pr?->park;
          $car      = $park?->park_car?->first();
          $owner    = $car?->car_belongs?->first()?->belongs_owner;
        @endphp
        <tr>
          <td>{{ $r->id_register }}</td>
          <td>{{ $svc->name }}</td>
          <td>${{ number_format($svc->price_net, 0, ',', '.') }}</td>
          <td>{{ ucfirst(str_replace('_',' ',$svc->type_service)) }}</td>
          <td>{{ $car->patent ?? '-' }}</td>
          <td>{{ $owner->name ?? '-' }}</td>
          <td>
            <button
              type="button"
              class="btn btn-sm btn-primary"
              data-toggle="modal"
              data-target="#paymentModal"
              data-register-id="{{ $r->id_register }}"
              data-service-name="{{ $svc->name }}"
              data-price="{{ $r->total_value }}"   {{-- o $svc->price_net si prefieres --}}
              data-patent="{{ $car->patent ?? '' }}"
              data-owner="{{ $owner->name ?? '' }}"
            >
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

      {{-- Ahora pasamos id_register, que es lo que valida tu store --}}
      <input type="hidden" name="id_register" id="modalRegisterId">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Registrar Pago</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          {{-- Servicio --}}
          <div class="form-group">
            <label>Servicio</label>
            <input type="text" id="modalServiceName" class="form-control" readonly>
          </div>

          {{-- Patente --}}
          <div class="form-group">
            <label>Patente</label>
            <input type="text" id="modalPatentDisplay" class="form-control" readonly>
          </div>

          {{-- Dueño --}}
          <div class="form-group">
            <label>Dueño</label>
            <input type="text" id="modalOwnerDisplay" class="form-control" readonly>
          </div>

          {{-- Total --}}
          <div class="form-group">
            <label>Total</label>
            <input type="text" name="amount" id="modalAmount" class="form-control" readonly>
          </div>

          {{-- Método de Pago --}}
          <div class="form-group">
            <label>Método de Pago</label>
            <select name="type_payment" id="modalTypePayment" class="form-control" required>
              <option value="">-- Selecciona --</option>
              <option value="efectivo">Efectivo</option>
              <option value="tarjeta">Tarjeta</option>
              <option value="transferencia">Transferencia</option>
            </select>
          </div>

          {{-- Fecha de Pago --}}
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
          <button type="button" class="btn btn-secondary mr-1" data-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-success">
            Guardar
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
    const btn           = $(e.relatedTarget);
    const registerId    = btn.data('register-id');
    const serviceName   = btn.data('service-name');
    const price         = btn.data('price');
    const patent        = btn.data('patent');
    const owner         = btn.data('owner');

    $('#modalRegisterId').val(registerId);
    $('#modalServiceName').val(serviceName);
    $('#modalPatentDisplay').val(patent);
    $('#modalOwnerDisplay').val(owner);
    $('#modalAmount').val(price);
    $('#modalTypePayment').val('');
    $('#modalPaymentDate').val(new Date().toISOString().slice(0,10));
  });
</script>
@endpush