{{-- resources/views/tenant/admin/roles/index.blade.php --}}
@extends('tenant.layouts.admin')

@section('title','Roles')
@section('page_title','Roles del sistema')

@push('styles')
  <!-- DataTables Bootstrap4 CSS -->
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"
  />
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
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <i class="fas fa-user-shield mr-2"></i>Roles
        </div>
        @role('SuperAdmin')
          <a href="{{ route('roles.create') }}" style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
            <i class="fas fa-plus"></i> Nuevo
          </a>
        @endrole
      </div>
    </div>

    <div class="card-body">
      <table id="roles-table" class="table table-striped w-100">
        <thead>
          <tr>
            <th>Rol</th>
            <th>Permisos</th>
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
  document.addEventListener('DOMContentLoaded', () => {
    $('#roles-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route("roles.data") }}'
      },
      columns: [
        { data: 'name',               name: 'name' },
        { data: 'permissions_count',  name: 'permissions_count' },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false,
          className: 'text-center'
        }
      ],
      order: [[0, 'asc']],
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });
  });
  </script>
@endpush
