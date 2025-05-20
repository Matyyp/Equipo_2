@extends('tenant.layouts.admin')

@section('title', 'Propietarios')
@section('page_title', 'Listado de Propietarios')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center">
        <span class="fw-semibold">
          <i class="fas fa-user-tie me-2"></i>Propietarios Registrados
        </span>
        <a href="{{ route('dueños.create') }}" class="btn btn-sm btn-success">
          <i class="fas fa-user-plus me-1"></i> Nuevo Propietario
        </a>
      </div>
    </div>

    <div class="card-body">
      @if ($owner->count())
        <div class="table-responsive">
          <table id="owners-table" class="table table-striped table-bordered w-100">
            <thead class="thead-light">
              <tr>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($owner as $item)
                <tr>
                  <td>{{ $item->type_owner }}</td>
                  <td>{{ $item->name }}</td>
                  <td>{{ $item->number_phone }}</td>
                  <td class="text-center">
                    <a href="{{ route('dueños.edit', $item->id_owner) }}" class="btn btn-sm btn-outline-info me-1">
                      <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('asociado.show', $item->id_owner) }}" class="btn btn-primary btn-sm mb-1">
                      <i class="fas fa-car"></i> Ver Autos
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="alert alert-info">No hay propietarios registrados.</div>
      @endif
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
