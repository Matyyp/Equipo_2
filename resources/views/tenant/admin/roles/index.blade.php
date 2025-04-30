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
@endpush

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-user-shield me-2"></i>Roles
      @role('Admin')
        <a href="{{ route('roles.create') }}"
           class="btn border btn-sm float-end">
          <i class="fas fa-plus"></i> Nuevo
        </a>
      @endrole
    </div>
    <div class="card-body">
      <table id="roles-table" class="table table-striped table-bordered w-100">
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
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });
  });
  </script>
@endpush
