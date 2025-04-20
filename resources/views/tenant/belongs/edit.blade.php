<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asociar Vehículos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Asociar Vehículos al Propietario</h2>

    {{-- Campo de búsqueda por patente --}}
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Buscar por patente...">

    <form action="{{ route('asociado.store') }}" method="POST">
        @csrf
        {{-- ID del propietario --}}
        <input type="hidden" name="id_owner" value="{{ $id }}">

        <table class="table table-bordered table-hover" id="vehiculosTable">
            <thead class="table-light">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>ID Vehículo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Patente</th>
                </tr>
            </thead>
            <tbody>
                @forelse($datos as $car)
                    <tr>
                        <td><input type="checkbox" name="vehiculos[]" value="{{ $car['id_car'] }}"></td>
                        <td>{{ $car['id_car'] }}</td>
                        <td>{{ $car['brand'] ?? '-' }}</td>
                        <td>{{ $car['model'] ?? '-' }}</td>
                        <td class="placa">{{ $car['patente'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay vehículos disponibles para asociar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <button type="submit" class="btn btn-success mt-3">Asociar Vehículos Seleccionados</button>
    </form>
</div>

{{-- JavaScript para búsqueda por patente y seleccionar todos --}}
<script>
    // Filtro por patente
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#vehiculosTable tbody tr');

        rows.forEach(function (row) {
            let placa = row.querySelector('.placa')?.textContent.toLowerCase();
            row.style.display = placa.includes(filter) ? '' : 'none';
        });
    });

    // Seleccionar todos los checkboxes
    document.getElementById('selectAll').addEventListener('change', function () {
        let checked = this.checked;
        document.querySelectorAll('input[name="vehiculos[]"]').forEach(function (cb) {
            cb.checked = checked;
        });
    });
</script>
</body>
</html>
