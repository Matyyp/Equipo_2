{{-- resources/views/tenant/admin/maintainer/payment/index.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Pagos Registrados')
@section('page_title', 'Pagos Registrados')

@push('styles')
<link
  rel="stylesheet"
  href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"
/>
@endpush

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">
                <i class="fas fa-money-check-alt me-2"></i> Historial de Pagos
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="payment-table" class="table table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Tipo Pago</th>
                            <th>Servicio</th>
                            <th>Precio Servicio</th>
                            <th>Patente</th>
                            <th>Dueño</th>
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
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
$(document).ready(function () {
    $('#payment-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: '{{ route("payment.index") }}',
        columns: [
            { data: 'id_payment',     title: 'Id' },
            { data: 'payment_date',   title: 'Fecha' },
            { data: 'amount',         title: 'Monto' },
            { data: 'type_payment',   title: 'Tipo Pago' },
            { data: 'service_name',   title: 'Servicio' },
            { data: 'price_net',      title: 'Precio Servicio' },
            { data: 'car_patent',     title: 'Patente' },
            { data: 'owner_name',     title: 'Dueño' },
            {
                // Columna “Acciones”
                data: null,
                orderable: false,
                searchable: false,
                title: 'Acciones',
                render: function (row) {
                    return `
                        <a
                          href="/payment/${row.id_payment}/voucher"
                          class="btn btn-sm btn-outline-primary"
                          title="Generar Voucher"
                        >
                          <i class="fas fa-file-invoice-dollar"></i>
                        </a>
                    `;
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-CL.json'
        }
    });

    // Confirmación opcional al eliminar
    $(document).on('submit', '.delete-form', function (e) {
        if (!confirm('¿Estás seguro de que deseas eliminar este registro?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
