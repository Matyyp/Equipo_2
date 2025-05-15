@extends('tenant.layouts.admin')

@section('title', 'Reglas de contratos')
@section('page_title', 'Listado de Reglas')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <i class="fas fa-gavel" style="margin-right: 8px;"></i>
          <h5 class="mb-0 fw-semibold">Reglas Registradas</h5>
        </div>
        <a href="{{ route('reglas.create') }}" class="btn btn-sm btn-success">
          <i class="fas fa-plus-circle me-1"></i> Nueva Regla
        </a>
      </div>
    </div>



    <div class="card-body">
      @if ($Rule->count())
        <div class="table-responsive">
          <table id="rules-table" class="table table-striped table-bordered w-100">
            <thead class="thead-light">
              <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Tipo de Contrato</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($Rule as $rule)
                <tr>
                  <td>{{ $rule->name }}</td>
                  <td>{{ $rule->description }}</td>
                  <td>
                    @switch($rule->type_contract)
                      @case('rent')
                        <span class="badge bg-primary">Renta</span>
                        @break
                      @case('parking_daily')
                        <span class="badge bg-info text-dark">Estacionamiento Diario</span>
                        @break
                      @case('parking_annual')
                        <span class="badge bg-secondary">Estacionamiento Anual</span>
                        @break
                      @default
                        <span class="badge bg-light text-muted">Sin tipo</span>
                    @endswitch
                  </td>
                  <td class="text-center">
                    <a href="/reglas/{{ $rule->id_rule }}/edit" class="btn btn-sm btn-outline-info me-1" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>


                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="alert alert-info">No hay reglas registradas.</div>
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
    $('#rules-table').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });

    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar esta regla?',
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
