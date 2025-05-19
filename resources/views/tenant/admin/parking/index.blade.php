@extends('tenant.layouts.admin')

@section('title', 'Listado de Ingresos')
@section('page_title', 'Listado de Ingresos')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" />
@endpush

@section('content')
<div class="container mt-5">
<div class="card">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center flex-wrap w-100">

        <!-- Título -->
        <div class="d-flex align-items-center mb-2 mb-md-0">
          <i class="fas fa-history me-2"></i>
          <span class="fw-semibold h6 mb-0">Listado de Estacionados</span>
        </div>

        <!-- Botones y alertas -->
        <div class="text-end">
          @php
            $bloqueado = !$empresaExiste || !$sucursalExiste;
          @endphp

          @if(!$empresaExiste)
            <div class="alert alert-warning d-inline-flex align-items-center gap-2 p-2 mb-2">
              <i class="fas fa-exclamation-triangle me-1"></i>
              <span>Debes registrar los <strong>datos de la empresa</strong>.</span>
              <a href="{{ route('empresa.index') }}" class="btn btn-sm btn-success ms-2">Ingresar datos empresa</a>
            </div>
          @elseif(!$sucursalExiste)
            <div class="alert alert-warning d-inline-flex align-items-center gap-2 p-2 mb-2">
              <i class="fas fa-exclamation-triangle me-1"></i>
              <span>Debes crear una <strong>sucursal</strong>.</span>
              <a href="{{ route('sucursales.index') }}" class="btn btn-sm btn-success ms-2">Crear sucursal</a>
            </div>
          @else
            <a href="{{ route('estacionamiento.create') }}" class="btn btn-sm btn-success">
              <i class="fas fa-car"></i> Ingresar vehículo
            </a>
          @endif
        </div>
      </div>
    </div>





    <div class="card-body">
      <div class="table-responsive">
        <table id="parking-table" class="table table-striped table-bordered nowrap w-100">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Patente</th>
              <th>Auto</th>
              <th>Inicio</th>
              <th>Término</th>
              <th>Días</th>
              <th>Lavado</th>
              <th>Precio Servicio</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Check-Out -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="checkout-form" method="POST">
      @csrf
      <input type="hidden" name="id_parking_register" id="checkout-id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="checkoutModalLabel">Confirmar Check-Out</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="checkout-total" class="form-label">Total a pagar</label>
            <input type="text" id="checkout-total" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label for="payment-method" class="form-label">Método de pago</label>
            <select name="type_payment" id="payment-method" class="form-control" required>
              <option value="">— Selecciona —</option>
              <option value="efectivo">Efectivo</option>
              <option value="tarjeta">Tarjeta</option>
              <option value="transferencia">Transferencia</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Confirmar Check-Out</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>

<script>
  $(function() {
    const table = $('#parking-table').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route("estacionamiento.index") }}',
        dataSrc: 'data',
        error: function(xhr, error, thrown) {
          console.error('AJAX Error:', xhr.status, thrown);
          alert(`Error cargando datos: ${xhr.status} – ${thrown}`);
        }
      },
      columns: [
        { data: 'owner_name' },
        { data: 'patent' },
        { data: 'brand_model' },
        { data: 'start_date' },
        { data: 'end_date' },
        { data: 'days' },
        { 
          data: 'washed',
          render: function(data) {
            return data 
              ? '<span class="">Sí</span>' 
              : '<span class="">No</span>';
          }
        },
        { data: 'service_price' },
        { data: 'total_formatted' },
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function(row) {
            return `
              <a href="/contrato/${row.id_parking_register}/print" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="Contrato">
                <i class="fas fa-file-contract"></i>
              </a>
              <a href="/ticket/${row.id_parking_register}/print" class="btn btn-sm btn-outline-secondary me-1" title="Ticket">
                <i class="fas fa-ticket-alt"></i>
              </a>
              <a href="/estacionamiento/${row.id_parking_register}/edit" class="btn btn-sm btn-outline-info me-1" title="Editar">
                <i class="fas fa-edit"></i>
              </a>
              <button 
                class="btn btn-sm btn-outline-success btn-checkout" 
                title="Check-Out"
                data-id="${row.id_parking_register}"
                data-total="${row.total_value}"
              >
                <i class="fas fa-sign-out-alt"></i>
              </button>
            `;
          }
        }
      ],
      order: [[3, 'desc']],
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });

    // Abrir modal y setear valores
    $('#parking-table').on('click', '.btn-checkout', function() {
      const id = $(this).data('id');
      const total = $(this).data('total');

      $('#checkout-form').attr('action', `/estacionamiento/${id}/checkout`);
      $('#checkout-id').val(id);
      $('#checkout-total').val(
        new Intl.NumberFormat('es-CL').format(total)
      );
      $('#checkoutModal').modal('show');
    });
  });
</script>
@endpush
