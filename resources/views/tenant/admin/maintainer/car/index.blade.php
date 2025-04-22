@extends('tenant.layouts.admin')

@section('title', 'Listado de Autos')
@section('page_title', 'Listado de Autos')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Autos registrados</h3>
        <a href="{{ route('autos.create') }}" class="btn btn-success btn-sm float-right">
            <i class="fas fa-plus"></i> Registrar Nuevo Auto
        </a>
    </div>

    <div class="card-body p-0">
        @if ($car && count($car))
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Patente</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Valor Arriendo</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($car as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td>{{ $item['patent'] }}</td>
                                <td>{{ $item['brand'] }}</td>
                                <td>{{ $item['model'] }}</td>
                                <td>
                                    @if ($item['value_rent'])
                                        ${{ number_format($item['value_rent'], 0, ',', '.') }}
                                    @else
                                        <span class="text-muted">No disponible</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('autos.edit', ['auto' => $item['id']]) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>

                                    <form action="{{ route('autos.destroy', ['auto' => $item['id']]) }}" method="POST" class="d-inline delete-form">
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
            <div class="alert alert-info m-3">No hay autos registrados.</div>
        @endif
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
                title: '¿Eliminar este auto?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
