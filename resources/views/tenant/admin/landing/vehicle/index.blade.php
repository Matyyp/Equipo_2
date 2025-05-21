@extends('tenant.layouts.admin')

@section('title', 'Tipos de Vehículo')
@section('page_title', 'Tipos de Vehículo')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-car-side mr-2"></i>Tipos de Vehículo</div>
      <a href="{{ route('landing.vehicle.create') }}" class="btn btn-outline-light btn-sm">
        <i class="fas fa-plus"></i> Nuevo
      </a>
    </div>
    <div class="card-body">
      <table id="vehicle-table" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>Imagen</th>
            <th>Título</th>
            <th>Subtítulo</th>
            <th>Estado Título</th>
            <th>Estado Subtítulo</th>
            <th>Color Fondo Tarjeta</th>
            <th>Color Texto</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

  <script>
    $(document).ready(() => {
      $('#vehicle-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("landing.vehicle.data") }}',
        columns: [
          { data: 'image', name: 'image', orderable: false, searchable: false },
          { data: 'card_title', name: 'card_title' },
          { data: 'card_subtitle', name: 'card_subtitle' },
          { data: 'card_title_active', name: 'card_title_active', className: 'text-center' },
          { data: 'card_subtitle_active', name: 'card_subtitle_active', className: 'text-center' },
          { data: 'card_background_color', name: 'card_background_color', orderable: false, searchable: false },
          { data: 'text_color', name: 'text_color', orderable: false, searchable: false },
          { data: 'acciones', name: 'acciones', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[1, 'asc']],
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
      });
    });
  </script>
@endpush
