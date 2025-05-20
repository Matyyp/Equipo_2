@extends('tenant.layouts.admin')

@section('title', 'Sucursales')
@section('page_title', 'Listado de Sucursales')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center">
        <span class="fw-semibold">
          <i class="fas fa-store-alt me-2"></i>Sucursales Registradas
        </span>

        @if ($verificacion)
          <a href="{{ route('sucursales.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus-circle me-1"></i> Nueva Sucursal
          </a>
        @else
          <div class="alert alert-warning mb-0 p-2 d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-2"></i> 
            Debe completar los datos de la empresa.
            <a href="{{ route('empresa.index') }}" class="btn btn-sm btn-success ms-2">Ingresar datos empresa</a>
          </div>
        @endif
      </div>
    </div>

    <div class="card-body">
      @if ($data->count())
        <div class="table-responsive">
          <table id="branches-table" class="table table-striped table-bordered w-100">
            <thead class="thead-light">
              <tr>
                <th>Sucursal</th>
                <th>Horario</th>
                <th>Calle</th>
                <th>Región</th>
                <th>Comuna</th>
                <th>Negocio</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data as $branch)
                <tr>
                  <td>{{ $branch['name_branch_offices'] }}</td>
                  <td>{{ $branch['schedule'] }}</td>
                  <td>{{ $branch['street'] }}</td>
                  <td>{{ $branch['region'] }}</td>
                  <td>{{ $branch['commune'] }}</td>
                  <td>{{ $branch['business'] }}</td>
                  <td class="text-center">
                    <a href="{{ route('sucursales.show', $branch['id']) }}" class="btn btn-secondary btn-sm">
                      <i class="fas fa-cog"></i> Configuración
                    </a>
                    <a href="{{ route('lavados.show', $branch['id']) }}" class="btn btn-info btn-sm mb-1">
                      <i class="fas fa-soap"></i> Lavado de auto
                    </a>

                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="alert alert-info">No hay sucursales registradas.</div>
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
  $(document).ready(function() {
    $('#branches-table').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });

    // Confirmación para eliminar
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar sucursal?',
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
