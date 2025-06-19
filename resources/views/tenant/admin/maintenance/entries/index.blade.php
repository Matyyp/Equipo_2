@extends('tenant.layouts.admin')

@section('title', 'Mantenciones')
@section('page_title', 'Gestión de Mantenciones')

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

  </style>
@endpush

@section('content')
<div class="container-fluid">

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
<div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center flex-wrap">
  {{-- Título con ícono a la izquierda --}}
  <div class="d-flex align-items-center">
    <i class="fas fa-tools mr-2"></i> Listado de Mantenciones
  </div>

  {{-- Botones a la derecha --}}
  <div class="d-flex flex-wrap ml-auto gap-2">
    <a href="{{ route('maintenance.entries.create') }}"
       style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
      <i class="fas fa-plus"></i> Nueva
    </a>
    <a href="#" data-toggle="modal" data-target="#programMaintenanceModal"
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
      <table id="maintenances-table" class="table table-striped w-100">
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
@endsection
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(function () {
  $('#maintenances-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('maintenance.entries.data') }}',
    columns: [
      { data: 'car', name: 'car' },
      { data: 'type', name: 'type' },
      { data: 'status', name: 'status', orderable: true, searchable: true },
      { data: 'proximidad', name: 'proximidad', orderable: true, searchable: false },
      { data: 'acciones', name: 'acciones', orderable: false, searchable: false, className: 'text-center' },
    ],
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
  });
});
</script>
@endpush

