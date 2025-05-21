@extends('tenant.layouts.admin')

@section('title', 'Modelos de Autos')
@section('page_title', 'Listado de Modelos de Autos')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center w-100">
        <span class="fw-semibold">
          <i class="fas fa-car-side mr-2"></i>Modelos Registrados
        </span>
        <a href="{{ route('modelo.create') }}"
          style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
          <i class="fas fa-plus"></i> Nuevo
        </a>
      </div>
    </div>


    <div class="card-body">
      <div class="table-responsive">
        <table id="models-table" class="table table-striped table-bordered w-100">
          <thead class="thead-light">
            <tr>
              <th>Nombre del Modelo</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($Modelcar as $model)
              <tr>
                <td>{{ $model->name_model }}</td>
                <td class="text-center">
                  <a href="{{ route('modelo.edit', $model->id_model) }}"
                    class="btn btn-outline-secondary btn-sm text-dark" title="Editar">
                    <i class="fas fa-pen"></i>
                  </a>
                </td>
              </tr>
            @empty
              {{-- Aquí se deja vacío para que DataTables lo maneje --}}
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
    $('#models-table').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });

    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar este modelo?',
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
