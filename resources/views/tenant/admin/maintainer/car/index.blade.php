@extends('tenant.layouts.admin')

@section('title', 'Listado de Autos')
@section('page_title', 'Listado de Autos')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center">
        <span class="fw-semibold">
          <i class="fas fa-car me-2"></i>Autos Registrados
        </span>
        <a href="{{ route('autos.create') }}" class="btn btn-sm btn-success">
          <i class="fas fa-plus-circle me-1"></i> Registrar Nuevo Auto
        </a>
      </div>
    </div>

    <div class="card-body">
      @if ($car && count($car))
        <div class="table-responsive">
          <table id="cars-table" class="table table-striped table-bordered w-100">
            <thead class="thead-light">
              <tr>
                <th>Patente</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Valor Arriendo</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($car as $item)
                <tr>
                  <td>{{ $item['patent'] }}</td>
                  <td>{{ $item['brand'] }}</td>
                  <td>{{ $item['model'] }}</td>
                  <td>
                    @if ($item['value_rent'])
                      ${{ number_format($item['value_rent'], 0, ',', '.') }}
                    @else
                      <span class="text-muted">No disponible</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <a href="{{ route('autos.edit', ['auto' => $item['id']]) }}" class="btn btn-sm btn-outline-info me-1">
                      <i class="fas fa-edit"></i> Editar
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="alert alert-info">No hay autos registrados.</div>
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
    $('#cars-table').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });

    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar este auto?',
          text: 'Esta acción no se puede deshacer.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
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
