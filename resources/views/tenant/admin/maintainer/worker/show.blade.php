@extends('tenant.layouts.admin')

@section('title', 'Empleados por Sucursal')
@section('page_title', 'Listado de Empleados')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center">
        <span class="fw-semibold">
          <i class="fas fa-users me-2"></i> Empleados asociados a la sucursal
        </span>
        <a href="{{ route('sucursales.show', $sucursal->id_branch) }}" class="btn btn-sm btn-light">
          <i class="fas fa-arrow-left me-1"></i> Volver al listado de Sucursales
        </a>
      </div>
    </div>

    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
          <i class="fas fa-store-alt me-1 text-muted"></i>
          Sucursal: <strong>{{ $sucursal->name_branch_offices }}</strong>
        </h5>

        <a href="{{ route('trabajadores.create', ['id_sucursal' => $sucursal->id_branch]) }}" class="btn btn-success btn-sm">
          <i class="fas fa-plus-circle me-1"></i> Agregar Trabajador
        </a>

      </div>


      <div class="table-responsive">
        <table id="empleados-table" class="table table-bordered table-striped w-100">
          <thead class="thead-dark">
            <tr>
              <th>Nombre</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Rol</th>
              <th>Fecha de Ingreso</th>
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
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
  $(document).ready(function() {
    const sucursalId = @json($sucursal->id_branch);

    $('#empleados-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('trabajadores/' . $sucursal->id_branch) }}",
        data: {
          is_sucursal: sucursalId
        }
      },
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      },
      columns: [
        { data: 'name', name: 'name' },
        { data: 'email', name: 'email' },
        { data: 'phone', name: 'phone' },
        { data: 'rol', name: 'rol' },
        { data: 'created_at', name: 'created_at' },
        { data: 'acciones', name: 'acciones', orderable: false, searchable: false, className: 'text-center' }
      ]

    });
  });
</script>
@endpush
