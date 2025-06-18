@extends('tenant.layouts.admin')

@section('title','Accidentes del Vehículo')
@section('page_title','Listado de Accidentes')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css"/>
  <style>
    #accidents-table th, #accidents-table td { vertical-align: middle; white-space: nowrap; }
    .card-header i { margin-right: 8px; }
    table.dataTable td, table.dataTable th { border: none !important; }
    table.dataTable tbody tr { border: none !important; }
    table.dataTable { border-top: 2px solid #dee2e6; border-bottom: 2px solid #dee2e6; }
    .dataTables_paginate .pagination .page-item.active a.page-link {
      background-color: #17a2b8 !important; color: #fff !important; border-color: #17a2b8 !important;
    }
    .dataTables_paginate .pagination .page-item .page-link {
      background-color: #eeeeee; color: #17a2b8 !important; border-color: #eeeeee;
    }
    .btn-outline-info.text-info:hover, .btn-outline-info.text-info:focus { color: #fff !important; }
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      background-color: #17a2b8;
      background-image: none;
      border-radius: 50%;
      width: 2.5rem;
      height: 2.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 8px rgba(0,0,0,0.18);
    }
    .carousel-control-prev-icon::after {
      content: '';
      display: block;
      width: 0.8rem;
      height: 0.8rem;
      mask: url("data:image/svg+xml;utf8,<svg fill='white' viewBox='0 0 8 8' xmlns='http://www.w3.org/2000/svg'><path d='M5.5 0l-4 4 4 4V0z'/></svg>") no-repeat center/contain;
      background: white;
      margin: auto;
    }
    .carousel-control-next-icon::after {
      content: '';
      display: block;
      width: 0.8rem;
      height: 0.8rem;
      mask: url("data:image/svg+xml;utf8,<svg fill='white' viewBox='0 0 8 8' xmlns='http://www.w3.org/2000/svg'><path d='M2.5 0v8l4-4-4-4z'/></svg>") no-repeat center/contain;
      background: white;
      margin: auto;
    }
    /* Botón volver abajo de la paginación, dentro de la tabla */
    .datatable-return-btn-wrapper {
      text-align: right;
      margin-top: 1rem;
      padding-right: 1.5rem;
    }
    .btn-volver {
      background: #6c757d !important; /* Gris de encabezado */
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
  </style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let alert = document.getElementById('success-alert');
    if(alert) {
      setTimeout(function() {
        alert.classList.remove('show');
        alert.classList.add('hide');
        setTimeout(() => alert.style.display = 'none', 300);
      }, 3000);
    }
  });
</script>
@endpush

@section('content')
<div class="container-fluid">
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
    {{ session('success') }}
  </div>
@endif

  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div>
        <i class="fas fa-car-crash"></i> Accidentes del Vehículo
        @if(isset($rentalCar))
          <span class="ml-2">| <b>Marca:</b> {{ $rentalCar->brand->name_brand ?? '' }} | <b>Modelo:</b> {{ $rentalCar->model->name_model ?? '' }}</span>
        @endif
      </div>
      <a href="{{ route('accidente.create', ['rental_car_id' => $rentalCar->id ?? null]) }}"
        style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Nuevo registro
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="accidents-table" class="table table-striped w-100">
          <thead>
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
        </table>
        <!-- Botón volver abajo del paginado, dentro de la tabla -->
        <div class="datatable-return-btn-wrapper">
          <a 
            href="{{ route('rental-cars.index') }}" 
            class="btn btn-volver">
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      $('#accidents-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route("accidente.data") }}',
          data: function (d) {
            d.rental_car_id = '{{ $rentalCar->id ?? "" }}';
          }
        },
        columns: [
          { data: 'id_rent', name: 'id_rent' },
          { data: 'name_accident', name: 'name_accident' },
          { data: 'description', name: 'description' },
          { data: 'bill_number', name: 'bill_number' },
          { data: 'description_accident_term', name: 'description_accident_term' },
          { data: 'photo', name: 'photo', orderable: false, searchable: false },
          { data: 'status', name: 'status' },
          { data: 'created_at', name: 'created_at' },
          {
              data: 'acciones',
              name: 'acciones',
              orderable: false,
              searchable: false,
              className: 'text-center'
          }
      ],
      order: [[7, 'desc']],
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        responsive: true
      });

      // Marcar como completado con SweetAlert2
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
                  $('#accidents-table').DataTable().ajax.reload(null, false);
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

      // SweetAlert2 para eliminar
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