@extends('tenant.layouts.admin')

@section('title', 'Ubicaciones')
@section('page_title', 'Listado de Ubicaciones')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ubicaciones registradas</h3>
        <a href="{{ route('locacion.create') }}" class="btn btn-success btn-sm float-right">
            <i class="fas fa-plus"></i> Ingresar Ubicación
        </a>
    </div>

    <div class="card-body p-0">
        @if ($data->count())
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Región</th>
                            <th>Comuna</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $location)
                            <tr>
                                <td>{{ $location->region }}</td>
                                <td>{{ $location->commune }}</td>
                                <td class="text-right">
                                    <a href="{{ route('locacion.edit', $location->id_location) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>

                                    <form action="{{ route('locacion.destroy', $location->id_location) }}"
                                          method="POST"
                                          class="d-inline delete-form">
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
            <div class="alert alert-info m-3">No hay ubicaciones registradas.</div>
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
                title: '¿Eliminar ubicación?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d'
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
