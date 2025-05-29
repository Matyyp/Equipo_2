@extends('tenant.layouts.admin')

@section('title', 'Empleados por Sucursal')
@section('page_title', 'Listado de Empleados')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-users mr-2"></i> Empleados asociados a la sucursal</div>
        <a href="{{ route('trabajadores.create', ['id_sucursal' => $sucursal->id_branch]) }}"
           style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
          <i class="fas fa-plus"></i> Agregar Trabajador
        </a>
    </div>

    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
          <i class="fas fa-store-alt mr-1 text-muted"></i>
          Sucursal: <strong>{{ $sucursal->name_branch_offices }}</strong>
        </h5>
      </div>

      <div class="table-responsive">
        <table id="empleados-table" class="table table-striped table-bordered w-100">
          <thead class="thead-light">
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
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('sucursales.show', $sucursal->id_branch) }}"
         style="background-color: transparent; border: 1px solid #6c757d; color: #6c757d; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
          <i class="fas fa-arrow-left mr-1"></i> Volver al listado de Sucursales
        </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
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
        {
          data: 'acciones',
          name: 'acciones',
          orderable: false,
          searchable: false,
          className: 'text-center'
        }
      ]
    });
  });
</script>
@endpush
