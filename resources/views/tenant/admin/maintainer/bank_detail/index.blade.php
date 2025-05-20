@extends('tenant.layouts.admin')

@section('title', 'Cuentas Bancarias')
@section('page_title', 'Listado de Cuentas Bancarias')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center">
        <span class="fw-semibold">
          <i class="fas fa-piggy-bank me-2"></i>Cuentas Bancarias
        </span>
        @if ($businessExists)
          <a href="{{ route('cuentas_bancarias.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus-circle me-1"></i> Nueva Cuenta
          </a>
        @else
          <div class="alert alert-warning mb-0">
            <i class="fas fa-info-circle me-1"></i>
            Debes registrar una empresa antes de agregar cuentas bancarias.
          </div>
        @endif

      </div>
    </div>

    <div class="card-body">
      <table id="bank-details-table" class="table table-bordered table-striped w-100">
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
        { data: 'action', orderable: false, searchable: false, className: 'text-center' }
      ],
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });
  });
  </script>
@endif

@endpush
