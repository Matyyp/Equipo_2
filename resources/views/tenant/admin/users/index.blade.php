{{-- resources/views/tenant/admin/users/index.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Usuarios')
@section('page_title', 'Usuarios del sistema')

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
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <i class="fas fa-users me-2"></i>Usuarios
        </div>
        @can('users.create')
          <a href="{{ route('users.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-user-plus"></i> Nuevo
          </a>
        @endcan
      </div>
    </div>

    <div class="card-body">
      <table id="users-table" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
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
    $('#users-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route("users.data") }}'
      },
      columns: [
        { data: 'name',  name: 'name' },
        { data: 'email', name: 'email' },
        { data: 'role',  name: 'roles.name', orderable: false, searchable: false },
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
