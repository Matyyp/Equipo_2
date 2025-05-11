@extends('tenant.layouts.admin')

@section('title', 'Tipos de Cuenta')
@section('page_title', 'Listado de Tipos de Cuenta')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center">
        <span class="fw-semibold">
          <i class="fas fa-wallet me-2"></i>Tipos de Cuenta
        </span>
        <a href="{{ route('tipo_cuenta.create') }}" class="btn btn-sm btn-success">
          <i class="fas fa-plus-circle me-1"></i> Nuevo
        </a>
      </div>
    </div>
    <div class="card-body">
      <table id="type-accounts-table" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>Nombre del Tipo</th>
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
    $('#type-accounts-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route("tipo_cuenta.data") }}',
      columns: [
        { data: 'name_type_account', name: 'name_type_account' },
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
