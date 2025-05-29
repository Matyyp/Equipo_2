@extends('tenant.layouts.admin')

@section('title', 'Cuentas Bancarias')
@section('page_title', 'Listado de Cuentas Bancarias')

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
      <div>
        <i class="fas fa-piggy-bank mr-2"></i>Cuentas Bancarias
      </div>

      @if ($businessExists)
        <a href="{{ route('cuentas_bancarias.create') }}"
           style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
          <i class="fas fa-plus"></i> Nueva
        </a>
      @else
        <div class="text-warning small font-weight-bold">
          <i class="fas fa-info-circle mr-1"></i> Debes registrar una empresa antes de agregar cuentas.
        </div>
      @endif
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="bank-details-table" class="table table-striped w-100">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>RUT</th>
              <th>N° Cuenta</th>
              <th>Banco</th>
              <th>Tipo Cuenta</th>
              <th class="text-center">Acciones</th>
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

  @if ($businessExists)
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    $('#bank-details-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route("cuentas_bancarias.data") }}',
      columns: [
        { data: 'name' },
        { data: 'rut' },
        { data: 'account_number' },
        { data: 'bank' },
        { data: 'type' },
        {
          data: 'action',
          orderable: false,
          searchable: false,
          className: 'text-center'
        }
      ],
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      },
      responsive: true
    });
  });
  </script>
  @endif
@endpush
