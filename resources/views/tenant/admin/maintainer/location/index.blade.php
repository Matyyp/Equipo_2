@extends('tenant.layouts.admin')

@section('title', 'Ubicaciones')
@section('page_title', 'Listado de Ubicaciones')
@push('styles')
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
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-map-marker-alt mr-2"></i>Ubicaciones registradas</div>
      <a href="{{ route('locacion.create') }}"
         style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Nuevo
      </a>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="locations-table" class="table table-striped w-100">
          <thead class="thead-light">
            <tr>
              <th>Región</th>
              <th>Comuna</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#locations-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("locacion.index") }}',
        columns: [
            { data: 'region', name: 'region' },
            { data: 'commune', name: 'commune' },
            {
              data: 'action',
              name: 'action',
              orderable: false,
              searchable: false,
              className: 'text-center'
            }
        ],
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });

    // Confirmación para eliminar
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: '¿Eliminar ubicación?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Notificación de éxito
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 2500,
            showConfirmButton: false
        });
    @endif
});
</script>
@endpush
