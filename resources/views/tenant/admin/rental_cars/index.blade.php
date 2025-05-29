{{-- resources/views/tenant/admin/rental_cars/index.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Autos de Arriendo')
@section('page_title', 'Autos de Arriendo')

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
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-car mr-2"></i>Autos de Arriendo</div>
      <a href="{{ route('rental-cars.create') }}"
         style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Nuevo
      </a>
    </div>
    <div class="card-body">
      <table id="cars-table" class="table table-striped w-100">
        <thead>
          <tr>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Estado</th>
            <th>Sucursal</th>
            <th>Precio</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <!-- jQuery + DataTables JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    $('#cars-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route("rental-cars.data") }}'
      },
      columns: [
        { data: 'marca',    name: 'brand.name_brand' },
        { data: 'modelo',   name: 'model.name_model' },
        { data: 'year',     name: 'year' },
        {
          data: 'estado',
          name: 'is_active',
          orderable: false,
          searchable: false
        },
        {
          data: 'sucursal',
          name: 'sucursal',
        },
        {
          data: 'price',
          name: 'price_per_day',
        },
        {
          data: 'acciones',
          name: 'acciones',
          orderable: false,
          searchable: false,
          className: 'text-center'
        }
      ],
      order: [[0, 'asc']],
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      },
      responsive: true
    });
  });
  </script>
@endpush
