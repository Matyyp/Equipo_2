@extends('tenant.layouts.admin')

@section('title', 'Reglas de contratos')
@section('page_title', 'Listado de Reglas')
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
      <div><i class="fas fa-gavel mr-2"></i>Reglas de contrato</div>
      <a href="{{ route('reglas.create') }}"
         style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Nuevo
      </a>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="rules-table" class="table table-striped w-100">
          <thead class="thead-light">
            <tr>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Tipo de Contrato</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($Rule as $rule)
              <tr>
                <td>{{ $rule->name }}</td>
                <td>{{ $rule->description }}</td>
                <td>
                  @switch($rule->type_contract)
                    @case('rent')
                      <span class="border border-success text-success px-2 py-1 rounded">Renta</span>
                      @break
                    @case('parking_daily')
                      <span class="border border-success text-success px-2 py-1 rounded">Estacionamiento Diario</span>
                      @break
                    @case('parking_annual')
                      <span class="border border-success text-success px-2 py-1 rounded">Estacionamiento Anual</span>
                      @break
                    @default
                      <span class="border border-muted text-muted px-2 py-1 rounded">Sin tipo</span>
                  @endswitch
                </td>

                <td class="text-center">
                  <a href="{{ route('reglas.edit', $rule->id_rule) }}"
                    class="btn btn-outline-info btn-sm text-info" title="Editar">
                    <i class="fas fa-pen"></i>
                  </a>
                </td>
              </tr>
            @empty
              {{-- Dejar vacío para que DataTables muestre el mensaje por defecto --}}
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
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
