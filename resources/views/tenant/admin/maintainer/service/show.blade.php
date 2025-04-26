@extends('tenant.layouts.admin')

@section('title', 'Servicios')
@section('page_title', 'Listado de Servicios')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Servicios Registrados</h3>
        <a href="{{ route('servicios.create') }}?sucursal_id={{ $sucursalId }}" class="btn btn-success btn-sm float-right">
            <i class="fas fa-plus"></i> Ingresar Servicio
        </a>
    </div>

    <div class="card-body p-0">
        @if ($data->count())
            <div class="table-responsive">
                <table class="table table-bordered mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio Neto</th>
                            <th>Tipo de Servicio</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $service)
                            <tr>
                                <td>{{ $service->id_service }}</td>
                                <td>{{ $service->name }}</td>
                                <td>${{ number_format($service->price_net, 0, ',', '.') }}</td>
                                <td>
                                    @switch($service->type_service)
                                        @case('parking') Estacionamiento @break
                                        @case('car_wash') Lavado de Autos @break
                                        @case('rent') Arriendo @break
                                        @default -
                                    @endswitch
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('servicios.edit', $service->id_service) }}" class="btn btn-warning btn-sm mb-1">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('servicios.destroy', $service->id_service) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info m-3">No hay servicios registrados.</div>
        @endif
    </div>

    <div class="card-footer">
        <a href="{{ route('sucursales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Sucursales
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar servicio?',
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

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 2500,
            showConfirmButton: false
        });
    @endif
</script>
@endpush
