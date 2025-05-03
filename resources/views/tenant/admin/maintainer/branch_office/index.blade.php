@extends('tenant.layouts.admin')

@section('title', 'Sucursales')
@section('page_title', 'Listado de Sucursales')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sucursales Registradas</h3>
        <a href="{{ route('sucursales.create') }}" class="btn btn-success btn-sm float-right">
            <i class="fas fa-plus"></i> Nueva Sucursal
        </a>
    </div>

    <div class="card-body p-0">
        @if ($data->count())
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Sucursal</th>
                            <th>Horario</th>
                            <th>Calle</th>
                            <th>Región</th>
                            <th>Comuna</th>
                            <th>Negocio</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $branch)
                            <tr>
                                <td>{{ $branch['id'] }}</td>
                                <td>{{ $branch['name_branch_offices'] }}</td>
                                <td>{{ $branch['schedule'] }}</td>
                                <td>{{ $branch['street'] }}</td>
                                <td>{{ $branch['region'] }}</td>
                                <td>{{ $branch['commune'] }}</td>
                                <td>{{ $branch['business'] }}</td>
                                <td class="text-right">
                                    <a href="{{ route('sucursales.edit', $branch['id']) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>

                                    <form action="{{ route('sucursales.destroy', $branch['id']) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>

                                    <a href="{{ route('servicios.show', $branch['id']) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-concierge-bell"></i> Servicios
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info m-3">No hay sucursales registradas.</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar sucursal?',
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
</script>
@endpush
