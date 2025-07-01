@extends('tenant.layouts.admin')

@section('title','Siniestros del Vehículo')
@section('page_title','Listado de Siniestros')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">
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
    table.dataTable td {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 180px;
    }
    @media (max-width: 576px) {
      .modal-dialog {
        max-width: 100vw;
        margin: 0;
      }
      .modal-content {
        border-radius: 0;
        min-height: 100vh;
      }
      .modal-body {
        padding: 0.5rem;
      }
      .modal .carousel-inner .carousel-item img {
        width: 100vw;
        height: auto;
        max-width: 100vw;
        object-fit: contain;
      }
    }
  </style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card bg-white shadow-sm">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center w-100">
        <div>
          <i class="fas fa-car-crash mr-2"></i>Siniestros del Vehículo
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
                <th>Nombre Siniestro</th>
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
            @foreach($accidents as $a)
            <tr>
              <td>{{ $a->id_rent ?? '-' }}</td>
              <td>{{ $a->name_accident }}</td>
              <td>{{ $a->description }}</td>
              <td>{{ $a->bill_number }}</td>
              <td>{{ $a->description_accident_term }}</td>
              <td>
                @if($a->photos->count() > 0)
                  <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modal-photo-{{ $a->id }}">
                    <i class="fas fa-image"></i> Ver Fotos
                  </button>
                @else
                  <span class="text-muted">Sin foto</span>
                @endif
              </td>
              <td>
                @if($a->status === 'in progress')
                  <span class="border border-secondary text-secondary px-2 py-1 rounded d-inline-flex align-items-center" style="height: 31px;">En progreso</span>
                @else
                  <span class="border border-success text-success px-2 py-1 rounded d-inline-flex align-items-center" style="height: 31px;">Completado</span>
                @endif
              </td>
              <td>{{ $a->created_at ? $a->created_at->format('Y-m-d H:i') : '' }}</td>
              <td class="text-center">
                <a href="{{ route('accidente.edit', $a->id) }}" class="btn btn-sm btn-outline-info" title="Editar"><i class="fas fa-pen"></i></a>
                <form action="{{ route('accidente.destroy', $a->id) }}" method="POST" style="display:inline-block;" class="form-delete-accident" data-id="{{ $a->id }}">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-sm btn-outline-info btn-delete-accident" data-id="{{ $a->id }}" title="Eliminar">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
                @if($a->status === 'in progress')
                  <button class="btn btn-sm btn-outline-info btn-mark-complete" data-id="{{ $a->id }}" title="Marcar como completado">
                    <i class="fas fa-check"></i>
                  </button>
                @endif
                <a href="{{ route('accidente.downloadPdf', $a->id) }}" class="btn btn-sm btn-outline-info" title="Descargar PDF" target="_blank">
                  <i class="fas fa-file-pdf"></i>
                </a>
              </td>
            </tr>
            @endforeach
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

<!-- MODALES DE FOTOS FUERA DE LA TABLA -->
@foreach($accidents as $a)
  <div class="modal fade" id="modal-photo-{{ $a->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-photo-{{ $a->id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-photo-{{ $a->id }}Label">Fotos del Siniestro</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          @if($a->photos->count() > 0)
            <div id="carousel-{{ $a->id }}" class="carousel slide" data-ride="carousel">
              @if($a->photos->count() > 1)
                <ol class="carousel-indicators">
                  @foreach($a->photos as $i => $photo)
                    <li data-target="#carousel-{{ $a->id }}" data-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></li>
                  @endforeach
                </ol>
              @endif
              <div class="carousel-inner">
                @foreach($a->photos as $i => $photo)
                  <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                    <img src="{{ tenant_asset($photo->photo) }}" alt="Foto siniestro" style="max-width:100%;height:auto;border-radius:4px;border:1px solid #ddd;">
                  </div>
                @endforeach
              </div>
              @if($a->photos->count() > 1)
                <a class="carousel-control-prev" href="#carousel-{{ $a->id }}" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#carousel-{{ $a->id }}" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Siguiente</span>
                </a>
              @endif
            </div>
          @else
            <span class="text-muted">Sin fotos</span>
          @endif
        </div>
      </div>
    </div>
  </div>
@endforeach

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
      responsive: true,
      language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' }
    });

    // Marcar como completado
    $(document).on('click', '.btn-mark-complete', function() {
      var id = $(this).data('id');
      Swal.fire({
        title: '¿Marcar como completado?',
        text: "¿Seguro que deseas marcar este siniestro como completado?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007bff',
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
                Swal.fire({
                  icon: 'success',
                  title: '¡Completado!',
                  text: 'El siniestro ha sido marcado como completado.',
                  confirmButtonColor: '#17a2b8'
                }).then(() => {
                  location.reload();
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
        confirmButtonColor: '#007bff',
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