@extends('tenant.layouts.admin')

@section('title', 'Modelos de Autos')
@section('page_title', 'Listado de Modelos de Autos')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Modelos Registrados</h3>
        <a href="{{ route('modelo.create') }}" class="btn btn-success btn-sm float-right">
            <i class="fas fa-plus"></i> Nuevo Modelo
        </a>
    </div>

    <div class="card-body p-0">
        @if ($Modelcar->count())
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre del Modelo</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($Modelcar as $model)
                            <tr>
                                <td>{{ $model->name_model }}</td>
                                <td class="text-right">
                                    <a href="{{ route('modelo.edit', $model->id_model) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('modelo.destroy', $model->id_model) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
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
            <div class="alert alert-info m-3">No hay modelos registrados.</div>
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
                title: '¿Eliminar este modelo?',
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
