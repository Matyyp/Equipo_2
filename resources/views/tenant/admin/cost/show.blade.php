@extends('tenant.layouts.admin')

@section('title', 'Costos de Servicios Básicos')
@section('page_title', 'Costos de Servicios Básicos')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" />
@endpush

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <span class="fw-semibold h6 mb-0"><i class="fas fa-file-invoice-dollar mr-2"></i>Costos de Servicios Básicos</span>
      <a href="{{ route('cost_basic_service.create') }}" 
        style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Registrar Costo
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="cost-table" class="table table-striped table-bordered nowrap w-100">
          <thead>
            <tr>

              <th>Sucursal</th>
              <th>Nombre</th>
              <th>Valor</th>
              <th>Fecha</th>
              <th>Nota</th>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    var table = $('#cost-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{{ route("cost_basic_service.data") }}',
        columns: [
            { data: 'branch', name: 'branch' },
            { data: 'name', name: 'name' },
            { data: 'value', name: 'value',
                render: function(data) {
                    return '$ ' + parseFloat(data).toLocaleString('es-CL');
                }
            },
            { data: 'date', name: 'date' },
            { data: 'note', name: 'note' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });

    // Evento eliminar
    $('#cost-table').on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Seguro que deseas eliminar este costo?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('costos') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: result.message || 'Costo eliminado con éxito'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al eliminar'
                        });
                    }
                });
            }
        });
    });


    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endpush