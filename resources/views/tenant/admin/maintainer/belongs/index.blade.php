@extends('tenant.layouts.admin')

@section('title', 'Autos del Propietario')

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
    <div class="d-flex justify-content-between align-items-center w-100">
        <h5 class="mb-0">
        <i class="fas fa-car me-2"></i> Autos asociados al propietario
        </h5>
        <a href="{{ route('asociado.edit', $id) }}" class="btn btn-success">
        <i class="fas fa-car"></i> Asociar Auto
        </a>
    </div>
    </div>


    <div class="card-body">
      @if (count($datos) === 0)
        <div class="alert alert-info">No hay autos asociados a este propietario.</div>
      @else
        <div class="table-responsive">
          <table id="autos-table" class="table table-bordered table-striped w-100">
            <thead class="thead-light">
              <tr>
                <th>Patente</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($datos as $item)
                <tr>
                  <td>{{ $item['patent'] }}</td>
                  <td>{{ $item['brand'] }}</td>
                  <td>{{ $item['model'] }}</td>
                  <td class="text-center">
                    <form action="{{ route('asociado.destroy', $item['id']) }}" method="POST" class="d-inline delete-form">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    <div class="card-footer d-flex justify-content-end">
      <a href="{{ route('dueños.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {
    $('#autos-table').DataTable({
      responsive: true,
        language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });

    // Confirmación para eliminar
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar auto asociado?',
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
    });
  });
</script>
@endpush
