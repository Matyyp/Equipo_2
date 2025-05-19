@extends('tenant.layouts.admin') 

@section('title', 'Historial de Lavados')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" />
<style>
  #history-table th, #history-table td {
    vertical-align: middle;
    white-space: nowrap;
  }
  .card-header i {
    margin-right: 8px;
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
        <table id="wash-history-table" class="table table-striped table-bordered nowrap w-100">
          <thead>
            <tr>
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
                <button class="btn btn-outline-secondary btn-sm text-dark me-1 marcar-lavado" data-id="${id}">
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

    // SweetAlert2 para confirmar el cambio de estado
    $(document).on('click', '.marcar-lavado', function () {
      const id = $(this).data('id');

      Swal.fire({
        title: '¿Marcar como lavado?',
        text: 'Esta acción actualizará el estado del registro.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, marcar',
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
<script>
  const csrfToken = @json(csrf_token());
</script>
@endpush
