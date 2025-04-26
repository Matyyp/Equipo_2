@extends('tenant.layouts.admin')

@section('title', 'Propietarios')
@section('page_title', 'Listado de Propietarios')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Propietarios Registrados</h3>
        <a href="{{ route('dueños.create') }}" class="btn btn-success btn-sm float-right">
            <i class="fas fa-user-plus"></i> Nuevo Propietario
        </a>
    </div>

    <div class="card-body p-0">
        @if ($owner->count())
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($owner as $item)
                            <tr>
                                <td>{{ $item->type_owner }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->last_name }}</td>
                                <td>{{ $item->number_phone }}</td>
                                <td class="text-right">
                                    <a href="{{ route('dueños.edit', $item->id_owner) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>

                                    <form action="{{ route('dueños.destroy', $item->id_owner) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>

                                    <a href="{{ route('asociado.show', $item->id_owner) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-car"></i> Ver Autos
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info m-3">No hay propietarios registrados.</div>
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
                title: '¿Eliminar propietario?',
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
