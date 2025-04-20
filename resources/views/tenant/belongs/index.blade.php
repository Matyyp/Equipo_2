<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autos del Propietario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Autos asociados al propietario</h2>

    <a href="{{ route('asociado.edit', $id) }}" class="btn btn-success mt-3">
        Asociar
    </a>




    @if (empty($datos))
        <div class="alert alert-info">No hay autos asociados a este propietario.</div>
    @else
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Id</th>
                    <th>ID Auto</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datos as $item)
                    <tr>
                        <td>{{ $item['id'] }}</td>
                        <td>{{ $item['id_car'] }}</td>
                        <td>{{ $item['brand'] }}</td>
                        <td>{{ $item['model'] }}</td>
                        <td>
                            <form action="{{ route('asociado.destroy', $item['id']) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Botón para asociar autos --}}
        <a href="{{ route('asociado.create', $id) }}" class="btn btn-success mt-3">
            Asociar Autos
        </a>
    @endif

    <a href="{{ route('dueños.index') }}" class="btn btn-secondary mt-2">Volver</a>
</div>
</body>
</html>
