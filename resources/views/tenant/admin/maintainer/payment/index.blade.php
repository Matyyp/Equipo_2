@extends('tenant.layouts.admin')

@section('title', 'Pagos Registrados')
@section('page_title', 'Pagos Registrados')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <h5 class="mb-0">
        <i class="fas fa-money-check-alt mr-2"></i> Historial de Pagos
      </h5>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="payment-table" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Id</th>
              <th>Fecha</th>
              <th>Monto</th>
              <th>Tipo Pago</th>
              <th>Servicio</th>
              <th>Precio Servicio</th>
              <th>Patente</th>
              <th>Dueño</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
$(document).ready(function () {
    $('#payment-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: '{{ route("payment.index") }}',
        columns: [
            { data: 'id_payment' },
            { data: 'payment_date' },
            { data: 'amount' },
            { data: 'type_payment' },
            { data: 'service_name' },
            { data: 'price_net' },
            { data: 'car_patent' },
            { data: 'owner_name' },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function (row) {
                    return `
                      <a href="/payment/${row.id_payment}/voucher"
                         class="btn btn-outline-secondary btn-sm text-dark"
                         title="Generar Voucher">
                        <i class="fas fa-file-invoice-dollar"></i>
                      </a>
                    `;
                }
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true
    });

    $(document).on('submit', '.delete-form', function (e) {
        if (!confirm('¿Estás seguro de que deseas eliminar este registro?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
