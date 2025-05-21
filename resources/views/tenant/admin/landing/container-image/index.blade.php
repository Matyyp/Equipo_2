@extends('tenant.layouts.admin')

@section('title', 'Contenedor de Imágenes')
@section('page_title', 'Contenedor de Imágenes')

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
      <div><i class="fas fa-images mr-2"></i>Imágenes del Contenedor</div>
      <a href="{{ route('landing.container-image.create') }}" class="btn btn-outline-light btn-sm">
        <i class="fas fa-plus"></i> Nueva Imagen
      </a>
    </div>
    <div class="card-body">
      <table id="container-images-table" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>ID</th>
            <th>Imagen</th>
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
      $('#container-images-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("landing.container-image.data") }}',
        columns: [
          { data: 'id_image', name: 'id_image' },
          { data: 'image', name: 'image', orderable: false, searchable: false },
          { data: 'acciones', name: 'acciones', className: 'text-center', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],  // ordenar por ID descendente
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true
      });
    });
  </script>
@endpush