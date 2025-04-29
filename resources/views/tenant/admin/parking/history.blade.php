{{-- resources/views/tenant/admin/parking/parking_history.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Historial de Ingresos')
@section('page_title', 'Historial de Ingresos - Estacionamiento')

@push('styles')
  <!-- DataTables Bootstrap4 CSS -->
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"
  />
  <style>
    #history-table_wrapper .dataTables_filter {
      float: right;
    }
    #history-table_wrapper .dataTables_paginate {
      float: right;
    }
    #history-table th, #history-table td {
      vertical-align: middle;
    }
    .card-header i {
      margin-right: 8px;
    }
  </style>
@endpush

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
      <div>
        <i class="fas fa-history"></i> Historial de Ingresos
      </div>
      <div>
        <button onclick="location.reload()" class="btn btn-sm btn-light text-info">
          <i class="fas fa-sync-alt"></i> Actualizar
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="history-table" class="table table-bordered table-hover table-sm mb-0">
          <thead class="thead-light">
            <tr>
              <th>Nombre</th>
              <th>Teléfono</th>
              <th>Patente</th>
              <th>Marca</th>
              <th>Modelo</th>
              <th>Inicio</th>
              <th>Término</th>
              <th>Días</th>
              <th>Precio</th>
              <th>Total</th>
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

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    $('#history-table').DataTable({
      processing: true,
      serverSide: false,
      ajax: '{{ route("estacionamiento.history") }}',
      columns: [
        { data: 'owner_name' },
        { data: 'owner_phone' },
        { data: 'patent' },
        { data: 'brand' },
        { data: 'model' },
        { data: 'start_date' },
        { data: 'end_date' },
        { data: 'days' },
        { 
          data: 'price',
          render: function(data) {
            return '$' + parseInt(data).toLocaleString('es-CL');
          }
        },
        { 
          data: 'total_value',
          render: function(data) {
            return '$' + parseInt(data).toLocaleString('es-CL');
          }
        }
      ],
      order: [[5, 'desc']],
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });
  });
  </script>
@endpush
