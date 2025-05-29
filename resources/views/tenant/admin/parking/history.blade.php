{{-- resources/views/tenant/admin/parking/parking_history.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Historial de Ingresos')
@section('page_title', 'Historial de Ingresos - Estacionamiento')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" />
<style>
  #history-table th, #history-table td {
    vertical-align: middle;
    white-space: nowrap;
  }
  .card-header i {
    margin-right: 8px;
  }
</style>
<style>
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
      <div><i class="fas fa-history mr-2"></i> Historial de Ingresos</div>
    </div>
    
    <div class="card-body">
      <div class="table-responsive">
        <table id="history-table" class="table  table-striped table-hover table-sm w-100 nowrap">
          <thead class="thead-light">
            <tr>
              @role('SuperAdmin')
                <th>Sucursal</th>
              @endrole
              <th>Nombre</th>
              <th>Teléfono</th>
              <th>Patente</th>
              <th>Marca</th>
              <th>Modelo</th>
              <th>Inicio</th>
              <th>Término</th>
              <th>Días</th>
              <th>Incluye lavado</th>
              <th>Precio</th>
              <th>Total</th>
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

<script>
document.addEventListener('DOMContentLoaded', () => {
  const formatCLP = value => '$' + parseInt(value).toLocaleString('es-CL');
  const formatDate = value => {
    if (!value) return '-';
    const d = new Date(value);
    return new Intl.DateTimeFormat('es-CL').format(d);
  };

  $('#history-table').DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: '{{ route("estacionamiento.history") }}',
    columns: [
      @role('SuperAdmin')
        { data: 'branch_name', title: 'Sucursal' },
      @endrole
      { data: 'owner_name' },
      { data: 'owner_phone' },
      { data: 'patent' },
      { data: 'brand' },
      { data: 'model' },
      { data: 'start_date'},
      { data: 'end_date'},
      { data: 'days' },
      {
        data: 'washed',
        render: function(data) {
          return data
            ? '<span class="">Sí</span>'
            : '<span class="">No</span>';
        }
      },
      { data: 'price', render: formatCLP },
      { data: 'total_value', render: formatCLP },
      {
        data: 'id_parking_register', 
        orderable: false,
        searchable: false,
        render: function(id) {
          return `
            <a href="/contrato/${id}/print" target="_blank" class="btn btn-outline-info btn-sm text-info" title="Contrato">
              <i class="fas fa-file-contract"></i>
            </a>
            <a href="/ticket/${id}/print" class="btn btn-outline-info btn-sm text-info" title="Ticket">
              <i class="fas fa-ticket-alt"></i>
            </a>
          `;
        }
      }
    ],
    order: [[5, 'desc']],
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
  });
});
</script>
@endpush
