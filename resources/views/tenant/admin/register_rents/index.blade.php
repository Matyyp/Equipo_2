@extends('tenant.layouts.admin')

@section('title','Registros de Arriendo')
@section('page_title','Listado de Registros de Arriendo')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css"/>

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
      <div><i class="fas fa-file mr-2"></i>Registros de arriendo</div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="rents-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
          <thead>
            <tr>
              <th>RUT</th>
              <th>Cliente</th>
              <th>Auto</th>
              <th>Sucursal</th>
              <th>Desde</th>
              <th>Hasta</th>
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
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script>
$(function(){
  $('#rents-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{!! route('registro_renta.data') !!}',
    columns: [
      { data: 'client_rut', name: 'client_rut' },
      { data: 'client_name', name: 'client_name' },
      { data: 'auto', name: 'rentalCar.brand.name_brand' },
      { data: 'sucursal', name: 'rentalCar.branchOffice.name_branch_offices' },
      { data: 'start_date', name: 'start_date' },
      { data: 'end_date', name: 'end_date' },
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
    responsive: true,
  });
});
</script>
@endpush
