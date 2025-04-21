<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Autos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Listado de Autos</h1>

    {{-- Botón para crear nuevo auto --}}
    <a href="{{ route('autos.create') }}" class="btn btn-success mb-3">Registrar Nuevo Auto</a>

    @if ($car && count($car))
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>id</th>
                    <th>Patente</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Valor Arriendo</th>
                    <th>Acciones</th>
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
                    <td>
                        <a href="{{ route('autos.edit', ['auto' => $item['id']]) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('autos.destroy', ['auto' => $item['id']]) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    @else
        <div class="alert alert-info">No hay autos registrados.</div>
    @endif
</div>

{{-- SweetAlert para confirmación de eliminación --}}
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
</body>
</html>
