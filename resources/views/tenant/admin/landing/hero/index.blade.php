@extends('tenant.layouts.admin')

@section('title', 'Hero Landing')
@section('page_title', 'Hero Landing')

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
      <div><i class="fas fa-photo-video mr-2"></i>Hero Landing</div>
      <a href="{{ route('landing.hero.create') }}" class="btn btn-outline-light btn-sm">
        <i class="fas fa-plus"></i> Nuevo
      </a>
    </div>
    <div class="card-body">
      <table id="heroes-table" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>Imagen</th>
            <th>Título</th>
            <th>Subtítulo</th>
            <th>Estado Título</th>
            <th>Estado Subtítulo</th>
            <th>Estado Botón</th>
            <th>Color Botón</th>
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
      $('#heroes-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("landing.hero.data") }}',
        columns: [
          { data: 'image', name: 'image', orderable: false, searchable: false },
          { data: 'title', name: 'title' },
          { data: 'subtitle', name: 'subtitle' },
          { data: 'title_status', name: 'title_status', className: 'text-center' },
          { data: 'subtitle_status', name: 'subtitle_status', className: 'text-center' },
          { data: 'button_status', name: 'button_status', className: 'text-center' },
          { data: 'button_color', name: 'button_color', orderable: false, searchable: false },
          { data: 'text_color', name: 'text_color', orderable: false, searchable: false },
          { data: 'acciones', name: 'acciones', className: 'text-center', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],  // ordenar por título
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true
      });
    });
  </script>
@endpush
