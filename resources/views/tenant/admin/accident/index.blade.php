@extends('tenant.layouts.admin')

@section('title','Accidentes del Vehículo')
@section('page_title','Listado de Accidentes')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css"> {{-- Agregado CSS responsive --}}
  <style>
    table.dataTable td, table.dataTable th { border: none !important; }
    table.dataTable tbody tr { border: none !important; }
    table.dataTable { border-top: 2px solid #dee2e6; border-bottom: 2px solid #dee2e6; }
    .dataTables_paginate .pagination .page-item.active a.page-link {
      background-color: #17a2b8 !important; 
      color: #fff !important;
      border-color: #17a2b8 !important; 
    }
    .dataTables_paginate .pagination .page-item .page-link {
      background-color: #eeeeee;
      color: #17a2b8 !important;
      border-color: #eeeeee;
    }
    .btn-outline-info.text-info:hover, .btn-outline-info.text-info:focus { color: #fff !important; }
    .datatable-return-btn-wrapper {
      text-align: right;
      margin-top: 1rem;
      padding-right: 1.5rem;
    }
    .btn-volver {
      background: #6c757d !important;
      color: #fff !important;
      border: none;
      border-radius: 4px;
      padding: 6px 18px;
      font-size: 14px;
      transition: background 0.2s;
      text-decoration: none;
      display: inline-block;
    }
    .btn-volver:hover, .btn-volver:focus {
      background: #565e64 !important;
      color: #fff !important;
    }
    /* Controlar ancho y texto para responsividad */
    table.dataTable td {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 180px;
    }
  </style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card bg-white shadow-sm">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center w-100">
        <div>
          <i class="fas fa-car-crash mr-2"></i>Accidentes del Vehículo
          @if(isset($rentalCar))
            <span class="ml-2">| <b>Marca:</b> {{ $rentalCar->brand->name_brand ?? '' }} | <b>Modelo:</b> {{ $rentalCar->model->name_model ?? '' }}</span>
          @endif
        </div>
        @php
          $hasRents = false;
          if(isset($rentalCar)) {
            $hasRents = method_exists($rentalCar, 'registerRents') && $rentalCar->registerRents()->count() > 0;
          }
        @endphp
        @if(isset($rentalCar))
          @if($hasRents)
            <a href="{{ route('accidente.create', ['rental_car_id' => $rentalCar->id ?? null]) }}"
              style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
              <i class="fas fa-plus"></i> Nuevo registro
            </a>
          @else
            <div class="alert d-flex align-items-center gap-2 mb-0 py-2 px-2" style="background-color: #17a2b8;">
              <i class="fas fa-info-circle mr-2"></i>
              <span>Debe haber al menos un arriendo asociado al vehículo.</span>
              <a href="{{ route('registro-renta.create') }}"
                style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-2">
                <i class="fas fa-plus mr-1"></i> Ingresar un arriendo
              </a>
            </div>
          @endif
        @else
          <a href="{{ route('accidente.create') }}"
            style="background-color: transparent; border: 1px solid #17a2b8; color: #17a2b8; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
            <i class="fas fa-plus"></i> Nuevo registro
          </a>
        @endif
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="accidents-table" class="table table-striped w-100">
          <thead class="thead-light">
            <tr>
                <th>Número de arriendo</th>
                <th>Nombre Accidente</th>
                <th>Descripción</th>
                <th>N° Factura</th>
                <th>Descripción término</th>
                <th>Foto(s)</th>
                <th>Estado</th>
                <th>Fecha de registro</th>
                <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            {{-- DataTables poblará --}}
          </tbody>
        </table>
        <div class="datatable-return-btn-wrapper">
          <a href="{{ route('rental-cars.index') }}" class="btn btn-volver">
            Volver
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function() {
    var table = $('#accidents-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      ajax: {
        url: '{{ route("accidente.data") }}',
        data: function (d) {
          d.rental_car_id = '{{ $rentalCar->id ?? "" }}';
        }
      },
      columns: [
        { data: 'id_rent', responsivePriority: 1 },
        { data: 'name_accident', responsivePriority: 2 },
        { data: 'description', responsivePriority: 5 },
        { data: 'bill_number', responsivePriority: 6 },
        { data: 'description_accident_term', responsivePriority: 7 },
        { data: 'photo', orderable: false, searchable: false, responsivePriority: 100 },
        { data: 'status', responsivePriority: 3 },
        { data: 'created_at', responsivePriority: 4 },
        {
          data: 'acciones',
          orderable: false,
          searchable: false,
          className: 'text-center',
          responsivePriority: 100
        }
      ],
      order: [[7, 'desc']],
      language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' }
    });

    // Marcar como completado
    $(document).on('click', '.btn-mark-complete', function() {
      var id = $(this).data('id');
      Swal.fire({
        title: '¿Marcar como completado?',
        text: "¿Seguro que deseas marcar este accidente como completado?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#888',
        confirmButtonText: 'Sí, marcar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '/accidente/' + id + '/complete',
            type: 'POST',
            data: {
              _token: '{{ csrf_token() }}'
            },
            success: function(response) {
              if(response.success) {
                table.ajax.reload(null, false);
                Swal.fire({
                  icon: 'success',
                  title: '¡Completado!',
                  text: 'El accidente ha sido marcado como completado.',
                  confirmButtonColor: '#17a2b8'
                });
              }
            }
          });
        }
      });
    });

    // Eliminar con confirmación
    $(document).on('click', '.btn-delete-accident', function(e) {
      e.preventDefault();
      var button = $(this);
      var form = button.closest('form');
      Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#888',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
</script>
@endpush
