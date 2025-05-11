@extends('tenant.layouts.admin')

@section('title', 'Marcas')
@section('page_title', 'Listado de Marcas')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center">
        <span class="fw-semibold">
          <i class="fas fa-tags me-2"></i>Listado de Marcas
        </span>
        <a href="{{ route('marca.create') }}" class="btn btn-sm btn-success">
          <i class="fas fa-plus-circle me-1"></i> Nueva Marca
        </a>
      </div>
    </div>

    <div class="card-body">
      @if ($data->count())
        <div class="table-responsive">
          <table id="brands-table" class="table table-striped table-bordered w-100">
            <thead class="thead-light">
              <tr>
                <th>Nombre</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data as $brand)
                <tr>
                  <td>{{ $brand->name_brand }}</td>
                  <td class="text-center">
                    <a href="{{ route('marca.edit', $brand->id_brand) }}" class="btn btn-warning btn-sm">
                      <i class="fas fa-edit"></i> Editar
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="alert alert-info">No hay marcas registradas.</div>
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
    $('#brands-table').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });

    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar esta marca?',
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
