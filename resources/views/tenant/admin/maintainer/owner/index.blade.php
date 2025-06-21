@extends('tenant.layouts.admin')

@section('title', 'Propietarios')
@section('page_title', 'Listado de Propietarios')
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
      <div><i class="fas fa-user-tie mr-2"></i>Propietario registrados</div>
      <a href="{{ route('dueños.create') }}"
         style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Nuevo
      </a>
    </div>



      <div class="card-body">
        <div class="table-responsive">
          <table id="owners-table" class="table table-striped w-100">
            <thead class="thead-light">
              <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($owner as $item)
                <tr>
                  <td>{{ $item->name }}</td>
                  <td>{{ $item->number_phone }}</td>
                  <td class="text-center">
                    <a href="{{ route('dueños.edit', $item->id_owner) }}"
                      class="btn btn-outline-info btn-sm text-info" title="Editar">
                      <i class="fas fa-pen"></i>
                    </a>
                    <a href="{{ route('asociado.show', $item->id_owner) }}"
                      class="btn btn-outline-info btn-sm text-info" title="Ver Autos">
                      <i class="fas fa-car"></i>
                    </a>
                  </td>
                </tr>
              @empty
                {{-- Se puede dejar vacío o incluir un mensaje opcional dentro de la tabla --}}
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {
    $('#owners-table').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });

    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar propietario?',
          text: 'Esta acción no se puede deshacer.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then(result => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>
@endpush
