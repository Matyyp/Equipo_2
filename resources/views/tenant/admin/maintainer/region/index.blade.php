@extends('tenant.layouts.admin')

@section('title', 'Regiones')
@section('page_title', 'Listado de Regiones')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <span><i class="fas fa-map-marked-alt me-2"></i>Regiones</span>
      <a href="{{ route('region.create') }}" class="btn btn-sm btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Nueva Región
      </a>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="regions-table" class="table table-striped table-bordered w-100">
          <thead class="thead-light">
            <tr>
              <th>ID</th>
              <th>Nombre Región</th>
              <th class="text-center">Acciones</th>
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
    $('#regions-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route("region.data") }}'
      },
      columns: [
        { data: 'id', name: 'id' },
        { data: 'name_region', name: 'name_region' },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false,
          className: 'text-center'
        }
      ],
      order: [[1, 'asc']],
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });
  });
</script>
@endpush
