@extends('tenant.layouts.admin')

@section('title', 'Bancos')
@section('page_title', 'Listado de Bancos')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <div>
        <i class="fas fa-university me-2"></i> Bancos
    </div>
    <a href="{{ route('banco.create') }}" class="btn btn-light btn-sm">
        <i class="fas fa-plus-circle me-1"></i> Nuevo Banco
    </a>
    </div>

      @can('banks.create')
        <a href="{{ route('banco.create') }}" class="btn btn-light btn-sm">
          <i class="fas fa-plus-circle me-1"></i> Nuevo Banco
        </a>
      @endcan
    </div>
    <div class="card-body">
      <table id="banks-table" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre del Banco</th>
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
    $('#banks-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route("banco.data") }}'
      },
      columns: [
        { data: 'id_bank',     name: 'id_bank' },
        { data: 'name_bank',   name: 'name_bank' },
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
