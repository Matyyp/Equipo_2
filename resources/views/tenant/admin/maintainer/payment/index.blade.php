{{-- resources/views/tenant/admin/maintainer/payment/index.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Listado de Pagos')
@section('page_title', 'Pagos Registrados')

@push('styles')
  <!-- DataTables Bootstrap4 CSS -->
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"
  />
@endpush

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white d-flex align-items-center justify-content-between">
      <div>
        <i class="fas fa-table me-2"></i>Listado de Pagos
      </div>
      <a href="{{ route('payment.create') }}" class="btn btn-sm btn-light">
        <i class="fas fa-plus-circle me-1"></i>Registrar Pago
      </a>
    </div>
    <div class="card-body">
      <table id="payments-table" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>Servicio</th>
            <th>Voucher</th>
            <th>Monto</th>
            <th>Patente</th>
            <th>Dueño</th>
            <th>Tipo</th>
            <th>Fecha de Pago</th>
            <th>Acciones</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    $('#payments-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route("payment.index") }}',
        dataSrc: 'data'
      },
      columns: [
        { data: 'service',      name: 'service' },
        { data: 'voucher',      name: 'voucher' },
        { data: 'amount',       name: 'amount' },
        { data: 'patent',       name: 'patent' },
        { data: 'owner',        name: 'owner' },
        { data: 'type_payment', name: 'type_payment' },
        { data: 'payment_date', name: 'payment_date' },
        {
          data: 'id_payment',
          orderable: false,
          searchable: false,
          render: function(id) {
            return `
              <a href="/payment/${id}/edit"
                 class="btn btn-sm btn-outline-info me-1"
                 title="Editar">
                <i class="fas fa-edit"></i>
              </a>
              <form action="/payment/${id}"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('¿Seguro que deseas eliminar este pago?')">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit"
                        class="btn btn-sm btn-outline-danger"
                        title="Eliminar">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </form>
            `;
          }
        }
      ],
      order: [[6, 'desc']],  // ordena por Fecha de Pago
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });
  });
  </script>
@endpush
