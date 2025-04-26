@extends('tenant.layouts.admin')

@section('title', 'Autos del Propietario')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Autos asociados al propietario</h3>

        <a href="{{ route('asociado.edit', $id) }}" class="btn btn-success btn-sm float-right">
            <i class="fas fa-car"></i> Asociar Auto
        </a>
    </div>

    <div class="card-body p-0">
        @if (count($datos) === 0)
            <div class="alert alert-info m-3">No hay autos asociados a este propietario.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Auto</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datos as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td>{{ $item['id_car'] }}</td>
                                <td>{{ $item['brand'] }}</td>
                                <td>{{ $item['model'] }}</td>
                                <td class="text-right">
                                    <form action="{{ route('asociado.destroy', $item['id']) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="card-footer">
        <a href="{{ route('dueños.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
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
                title: '¿Eliminar auto asociado?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
