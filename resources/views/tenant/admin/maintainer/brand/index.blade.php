@extends('tenant.layouts.admin')

@section('title', 'Marcas')
@section('page_title', 'Listado de Marcas')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Listado de Marcas</h3>
        <a href="{{ route('marca.create') }}" class="btn btn-success btn-sm float-right">
            <i class="fas fa-plus"></i> Nueva Marca
        </a>
    </div>

    <div class="card-body p-0">
        @if ($data->count())
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $brand)
                            <tr>
                                <td>{{ $brand->name_brand }}</td>
                                <td class="text-right">
                                    <a href="{{ route('marca.edit', $brand->id_brand) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info m-3">No hay marcas registradas.</div>
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
                title: '¿Eliminar esta marca?',
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
