@extends('tenant.layouts.admin') 

@section('title', 'Historial de Lavados')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" />
<style>
  #wash-history-table th, #wash-history-table td {
    vertical-align: middle;
    white-space: nowrap;
  }
  .card-header i {
    margin-right: 8px;
  }
  table.dataTable td,
    table.dataTable th {
      border: none !important;
    }

    table.dataTable tbody tr {
      border: none !important;
    }

    /* Agregar solo el borde superior e inferior a la tabla completa */
    table.dataTable {
      border-top: 2px solid #dee2e6;
      border-bottom: 2px solid #dee2e6;
    }

    /* Agregar solo el borde superior e inferior a la tabla completa */
     /* Agregar solo el borde superior e inferior a la tabla completa */
    table.dataTable {
      border-top: 2px solid #dee2e6;
      border-bottom: 2px solid #dee2e6;
    }

    .dataTables_paginate .pagination .page-item.active .page-link {
      background-color: #17a2b8 !important; 
      border-color: #17a2b8 !important; 
    }
    .dataTables_paginate .pagination .page-item.disabled .page-link {
  background-color: #eeeeee;
  color: #17a2b8 !important;
  border-color: #eeeeee;
}
/* Cambia el texto a blanco al pasar el mouse por el botón de marcar lavado */
.btn-outline-info.text-info:hover, 
.btn-outline-info.text-info:focus {
  color: #fff !important;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-history mr-2"></i>
      <span class="fw-semibold h6 mb-0">Historial de Lavados</span>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="wash-history-table" class="table table-striped nowrap w-100">
          <thead>
            <tr>
              @role('SuperAdmin')
                <th>Sucursal</th>
              @endrole
              <th>Patente</th>
              <th>Tipo de Lavado</th>
              <th>¿Se Lavó?</th>
              <th>Precio ($)</th>
              <th>Acciones</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  const csrfToken = @json(csrf_token());

  $(function() {
    const table = $('#wash-history-table').DataTable({
      responsive: true,
      processing: true,
      serverSide: false,
      ajax: {
        url: '{{ route("carwash.history") }}',
        dataSrc: 'data'
      },
      columns: [
        @role('SuperAdmin')
          { data: 'branch_name', title: 'Sucursal' },
        @endrole
        { data: 'patent' },
        { data: 'wash_type' },
        {
          data: 'washed',
          render: function(data) {
            return data === 'Sí'
              ? '<span class="">Sí</span>'
              : '<span class="">No</span>';
          }
        },
        {
          data: 'price_net',
          render: function(data) {
            return new Intl.NumberFormat('es-CL', {
              style: 'currency',
              currency: 'CLP',
              minimumFractionDigits: 0
            }).format(data);
          }
        },
        {
          data: 'id_parking_register',
          orderable: false,
          searchable: false,
          render: function(id, type, row) {
            if (row.washed === 'No') {
              return `
                <button class="btn btn-outline-info btn-sm text-info me-1 marcar-lavado" data-id="${id}">
                  <i class="fas fa-check"></i> Marcar como Lavado
                </button>
              `;
            } else {
              return `<span class="text-muted">Ya Lavado</span>`;
            }
          }
        }
      ],
      order: [[0, 'asc']],
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });

    // SweetAlert para marcar como lavado
    $(document).on('click', '.marcar-lavado', function () {
      const id = $(this).data('id');

      Swal.fire({
        title: '¿Marcar como lavado?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: `/carwash/marcar-lavado/${id}`,
            method: 'PATCH',
            headers: {
              'X-CSRF-TOKEN': csrfToken
            },
            success: function (res) {
              Swal.fire({
                title: '¡Actualizado!',
                text: res.message,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
              });
              table.ajax.reload();
            },
            error: function () {
              Swal.fire({
                title: 'Error',
                text: 'No se pudo actualizar el estado.',
                icon: 'error'
              });
            }
          });
        }
      });
    });
  });
</script>
@endpush
