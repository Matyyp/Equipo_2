@extends('tenant.layouts.admin')

@section('title','Reservas Web')
@section('page_title','Listado de Reservas')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" />
<style>
  #reservations-table th, #reservations-table td {
    vertical-align: middle;
    white-space: nowrap;
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
    color: #fff !important;
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
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-calendar-alt mr-2"></i> Listado de Reservas</div>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="reservations-table" class="table table-striped table-hover table-sm w-100 dt-responsive nowrap">
          <thead class="thead-light">
            <tr>
              <th class="control"></th>
              <th>RUT</th>
              <th>Cliente</th>
              <th>Auto</th>
              <th>Sucursal</th>
              <th>Desde</th>
              <th>Hasta</th>
              <th>Estado</th>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  $('#reservations-table').DataTable({
    responsive: {
      details: {
        type: 'column',
        target: 0
      }
    },
    columnDefs: [
      {
        className: 'control',
        orderable: false,
        searchable: false,
        targets: 0
      },
      {
        targets: [3, 4, 5, 6, 7],
        responsivePriority: 100
      }
    ],
    processing: true,
    serverSide: true,
    ajax: '{!! route('reservations.data') !!}',
    columns: [
      { data: null, defaultContent: '', orderable: false, searchable: false },
      { data: 'rut', name: 'rut' },
      { data: 'cliente', name: 'user.name' },
      { data: 'auto', name: 'car.brand.name_brand' },
      { data: 'sucursal', name: 'branchOffice.name_branch_offices' },
      { data: 'desde', name: 'start_date' },
      { data: 'hasta', name: 'end_date' },
      { data: 'estado', name: 'status', orderable: false, searchable: false },
      { data: 'acciones', name: 'acciones', orderable: false, searchable: false, className: 'text-center' }
    ],
    order: [[5, 'desc']],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    }
  });
});
</script>
@endpush
