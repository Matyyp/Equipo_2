{{-- resources/views/tenant/admin/reservations/index.blade.php --}}
@extends('tenant.layouts.admin')

@section('title','Reservas Web')
@section('page_title','Listado de Reservas')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"/>

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
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-file mr-2"></i>Reservas Web</div>
    </div>
    <div class="card-body">
      <table id="reservations-table" class="table table-striped w-100">
        <thead>
          <tr>
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
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(function(){
  $('#reservations-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{!! route('reservations.data') !!}',
    columns: [
      { data: 'rut',      name: 'rut' },
      {
        data: 'cliente',
        name: 'user.name',        // <-- antes estaba 'user.email'
        orderable: true,
        searchable: true
      },
      { data: 'auto',      name: 'car.brand.name_brand' },
      { data: 'sucursal',  name: 'branchOffice.name_branch_offices' },
      { data: 'desde',     name: 'start_date' },
      { data: 'hasta',     name: 'end_date' },
      {
        data: 'estado',
        name: 'status',
        orderable: false,
        searchable: false
      },
      {
        data: 'acciones',
        name: 'acciones',
        orderable: false,
        searchable: false,
        className: 'text-center'
      },
    ],
    order: [[4, 'desc']],
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
    responsive: true
  });
});
</script>
@endpush
