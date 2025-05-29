@extends('tenant.layouts.admin')

@section('title', 'Pagos Registrados')
@section('page_title', 'Pagos Registrados')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" />
<style>
  #payment-table th, #payment-table td {
    vertical-align: middle;
    white-space: nowrap;
  }
  .card-header i {
    margin-right: 8px;
  }
  table.dataTable td,
    table.dataTable th {
      border: none !important;
    }

    table.dataTable tbody tr {
      border: none !important;
    }

    table.dataTable {
      border-top: 2px solid #dee2e6;
      border-bottom: 2px solid #dee2e6;
    }

    .dataTables_paginate .pagination .page-item.active a.page-link {
      background-color: #17a2b8 !important; 
      color:rgb(255, 255, 255) !important;
      border-color: #17a2b8 !important; 
    }


    .dataTables_paginate .pagination .page-item .page-link {
      background-color: #eeeeee;
      color: #17a2b8 !important;
      border-color: #eeeeee;
    }
    .btn-outline-info.text-info:hover,
.btn-outline-info.text-info:focus {
  color: #fff !important;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div>
        <i class="fas fa-money-check-alt"></i> Historial de Pagos
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="payment-table" class="table table-striped table-hover table-sm w-100 nowrap">
          <thead class="thead">
            <tr>
              <th>Id</th>
              <th>Fecha</th>
              <th>Monto</th>
              <th>Tipo Pago</th>
              <th>Servicio</th>
              <th>Precio Servicio</th>
              <th>Patente</th>
              <th>Dueño</th>
              <th>Acciones</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const formatCLP = value => '$' + parseInt(value).toLocaleString('es-CL');
  const formatDate = value => {
    if (!value) return '-';
    const d = new Date(value);
    return new Intl.DateTimeFormat('es-CL').format(d);
  };

  $('#payment-table').DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: '{{ route("payment.index") }}',
    columns: [
      { data: 'id_payment', title: 'Id' },
      { data: 'payment_date', title: 'Fecha', render: formatDate },
      { data: 'amount', title: 'Monto', render: formatCLP },
      { data: 'type_payment', title: 'Tipo Pago' },
      { data: 'service_name', title: 'Servicio' },
      { data: 'price_net', title: 'Precio Servicio', render: formatCLP },
      { data: 'car_patent', title: 'Patente' },
      { data: 'owner_name', title: 'Dueño' },
      {
        data: null,
        orderable: false,
        searchable: false,
        title: 'Acciones',
        render: function (row) {
          return `
            <a
              href="/payment/${row.id_payment}/voucher"
              class="btn btn-outline-info btn-sm text-info me-1"
              title="Generar Voucher"
              target="_blank"
            >
              <i class="fas fa-file-invoice-dollar"></i>
            </a>
          `;
        }
      }
    ],
    order: [[1, 'desc']],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    }
  });

  // Confirmación opcional al eliminar
  $(document).on('submit', '.delete-form', function (e) {
    if (!confirm('¿Estás seguro de que deseas eliminar este registro?')) {
      e.preventDefault();
    }
  });
});
</script>
@endpush