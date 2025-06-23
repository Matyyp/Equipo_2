{{-- resources/views/tenant/admin/maintenance/entries/index.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Mantenciones')
@section('page_title', 'Gestión de Mantenciones')

@push('styles')
  <!-- DataTables Bootstrap4 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" />

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
        color: rgb(255, 255, 255) !important;
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
      .btn-sm.btn-outline-info {
        padding-top: 6px !important;
        padding-bottom: 6px !important;
        font-size: 0.875rem;
        line-height: 1.5;
      }

      /* Hacer que los botones de acciones se oculten en pantallas pequeñas */
      /* Usamos Bootstrap 4 clases utilitarias para ocultar */
      .acciones-buttons > * {
        /* Ocultar por defecto */
        display: none;
      }
      @media (min-width: 768px) { /* md breakpoint */
        .acciones-buttons > * {
          display: inline-block;
          margin: 0 2px;
        }
      }
  </style>
@endpush

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center flex-wrap">
      <div class="d-flex align-items-center">
        <i class="fas fa-tools mr-2"></i> Listado de Mantenciones
      </div>
      <div class="d-flex flex-wrap ml-auto gap-2">
        <a href="{{ route('maintenance.entries.create') }}" class="mr-2"
           style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
          <i class="fas fa-plus"></i> Nueva
        </a>
        <a href="#" data-toggle="modal" data-target="#programMaintenanceModal" class="mr-2"
           style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
          <i class="fas fa-calendar-plus"></i> Nueva Programada
        </a>
        <a href="#" data-toggle="modal" data-target="#interruptScheduleModal" 
           style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
          <i class="fas fa-ban"></i> Interrumpir Programada
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        {{-- Agregamos clases dt-responsive nowrap para mejor responsive --}}
        <table id="maintenances-table" class="table table-striped w-100 dt-responsive nowrap">
          <thead>
            <tr>
              <th>Vehículo</th>
              <th>Mantención</th>
              <th>Estado</th>
              <th>Proximidad</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- Aquí quedan los modales igual, sin cambios --}}

@foreach(App\Models\Maintenance::with('car')->get() as $m)
  <!-- Modal -->
  <div class="modal fade" id="completeMaintenanceModal_{{ $m->id }}" tabindex="-1" role="dialog" aria-labelledby="completeModalLabel_{{ $m->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form method="POST" action="{{ route('maintenance.entries.complete', $m) }}" enctype="multipart/form-data" class="modal-content">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Marcar como completada</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label>Empleado responsable</label>
            <input type="text" name="employee_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Fecha de finalización</label>
            <input type="date" name="completed_date" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Lugar de mantención</label>
            <input type="text" name="location" class="form-control">
          </div>
          <div class="form-group">
            <label>N° de factura</label>
            <input type="text" name="invoice_number" class="form-control">
          </div>
          <div class="form-group">
            <label>Imagen de factura (opcional)</label>
            <input type="file" name="invoice_file" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Marcar como realizada</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
@endforeach
@foreach(App\Models\Maintenance::with('car', 'images', 'type')->get() as $m)
  <div class="modal fade" id="viewMaintenanceModal_{{ $m->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel_{{ $m->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Detalles de la Mantención</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <p><strong>Vehículo:</strong>  {{ $m->car->brand->name_brand ?? '-' }} - {{ $m->car->model->name_model ?? '-' }}</p>
          <p><strong>Tipo de mantención:</strong> {{ $m->type->name ?? '-' }}</p>
          <p><strong>Kilometraje programado:</strong> {{ $m->scheduled_km ?? '—' }}</p>
          <p><strong>Fecha programada:</strong> {{ $m->scheduled_date ?? '—' }}</p>

          @if($m->is_completed)
            <hr>
            <p><strong>Completado por:</strong> {{ $m->employee_name }}</p>
            <p><strong>Fecha de finalización:</strong> {{ $m->completed_date }}</p>
            <p><strong>Lugar:</strong> {{ $m->location }}</p>
            <p><strong>N° de factura:</strong> {{ $m->invoice_number }}</p>

            @if($m->images->first())
              <div class="mt-3">
                <strong>Factura:</strong><br>
                <img src="{{ tenant_asset($m->images->first()->image_path) }}" alt="Factura" class="img-fluid rounded" style="max-width: 100%;">
              </div>
            @endif
          @else
            <p class="text-muted">Esta mantención aún no está marcada como completada.</p>
          @endif
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>

      </div>
    </div>
  </div>
@endforeach
<!-- Modal Programar Mantenciones -->
<div class="modal fade" id="programMaintenanceModal" tabindex="-1" role="dialog" aria-labelledby="programModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('maintenance.entries.schedule') }}" method="POST" class="modal-content">
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Programar mantenciones por kilometraje</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="rental_car_id">Vehículo</label>
          <select name="rental_car_id" class="form-control" required>
            @foreach(\App\Models\RentalCar::where('is_active', 1)->get() as $car)
              <option value="{{ $car->id }}"> {{ $car->brand->name_brand }} - {{ $car->model->name_model }} ({{ $car->km }} km)</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="maintenance_type_id">Tipo de mantención</label>
          <select name="maintenance_type_id" class="form-control" required>
            @foreach(\App\Models\MaintenanceType::all() as $type)
              <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="interval_km">Cada cuántos KM</label>
          <input type="number" name="interval_km" class="form-control" min="1" required>
        </div>

        <div class="form-group">
          <label for="quantity">Cantidad de mantenciones a programar</label>
          <input type="number" name="quantity" class="form-control" min="1" max="20" value="10" required>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Programar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>
<!-- Modal Interrumpir programación -->
<div class="modal fade" id="interruptScheduleModal" tabindex="-1" role="dialog" aria-labelledby="interruptModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('maintenance.entries.interrupt') }}" method="POST" class="modal-content">
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Interrumpir programación de mantenciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="rental_car_id_interrupt">Vehículo</label>
          <select name="rental_car_id" class="form-control" required>
            @foreach(\App\Models\RentalCar::where('is_active', 1)->get() as $car)
              <option value="{{ $car->id }}">{{ $car->brand->name_brand }} - {{ $car->model->name_model }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="maintenance_type_id_interrupt">Tipo de mantención</label>
          <select name="maintenance_type_id" class="form-control" required>
            @foreach(\App\Models\MaintenanceType::all() as $type)
              <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
          </select>
        </div>

        <p class="text-muted small">Se eliminarán todas las mantenciones programadas futuras que NO estén completadas para el auto y tipo seleccionados.</p>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-danger">Interrumpir programación</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
 <!-- jQuery + DataTables JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
  <!-- DataTables Responsive JS -->
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function () {
  $('#maintenances-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: '{{ route('maintenance.entries.data') }}',
    columns: [
      { data: 'car', name: 'car', responsivePriority: 1 },
      { data: 'type', name: 'type', responsivePriority: 2 },
      { data: 'status', name: 'status', orderable: true, searchable: true, responsivePriority: 3 },
      { data: 'proximidad', name: 'proximidad', orderable: true, searchable: false, responsivePriority: 4 },
      {
        data: 'acciones',
        name: 'acciones',
        orderable: false,
        searchable: false,
        className: 'text-center acciones-buttons',
        responsivePriority: 10 
      },
    ],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
  });
});
</script>
<script>
$(document).ready(function () {
    // SweetAlert para eliminar
    $(document).on('submit', '.form-delete', function (e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: '¿Está seguro?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // SweetAlert para marcar como en mantenimiento
    $(document).on('submit', '.form-mark', function (e) {
        e.preventDefault();
        const form = this;

        Swal.fire({
            title: '¿Marcar vehículo en mantenimiento?',
            text: "El vehículo pasará a estado de mantención.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

@endpush
