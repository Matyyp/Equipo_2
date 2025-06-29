@extends('tenant.layouts.admin')

@section('title', 'Tipos de Mantención')
@section('page_title', 'Gestión de Tipos de Mantención')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
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

  </style>
@endpush
@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
      @if(session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
@endif
  <div class="card">

    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-cogs nav-icon mr-1"></i>Tipos de Mantención</div>
      <a href="{{ route('maintenance.type.create') }}" 
         style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Nuevo
      </a>
    </div>
    <div class="card-body">
      <table id="types-table" class="table table-striped w-100">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

@endsection

@push('scripts')
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

  <script>
    $(function () {
      $('#types-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("maintenance.type.data") }}',
        columns: [
          { data: 'name', name: 'name' },
          { data: 'description', name: 'description' },
          { data: 'acciones', name: 'acciones', className: 'text-center', orderable: false, searchable: false }
        ],
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true
      });
    });
  </script>
@endpush
