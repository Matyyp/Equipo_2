@extends('tenant.layouts.admin')

@section('title', 'Asociar Vehículos')
@section('page_title', 'Asociar Vehículos al Propietario')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Asociar Vehículos al Propietario</h3>
        <a href="{{ route('dueños.index') }}" class="btn btn-secondary btn-sm float-right">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card-body">
        {{-- Campo de búsqueda por patente --}}
        <div class="form-group mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por patente...">
        </div>

        <form action="{{ route('asociado.store') }}" method="POST" id="formAsociarVehiculos">
            @csrf
            <input type="hidden" name="id_owner" value="{{ $id }}">

            <div class="table-responsive">
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
                                <td colspan="5" class="text-center text-muted">No hay vehículos disponibles para asociar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i> Asociar Vehículos Seleccionados
                </button>
                <a href="{{ route('dueños.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Buscar por patente
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#vehiculosTable tbody tr');

        let visible = 0;
        rows.forEach(row => {
            const placa = row.querySelector('.placa')?.textContent.toLowerCase();
            const match = placa.includes(filter);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        const noResultRow = document.getElementById('noResultsRow');
        if (visible === 0 && !noResultRow) {
            const tbody = document.querySelector('#vehiculosTable tbody');
            const row = document.createElement('tr');
            row.id = 'noResultsRow';
            row.innerHTML = `<td colspan="5" class="text-center text-muted">No se encontraron coincidencias.</td>`;
            tbody.appendChild(row);
        } else if (visible > 0 && noResultRow) {
            noResultRow.remove();
        }
    });

    // Seleccionar todos los checkboxes
    document.getElementById('selectAll').addEventListener('change', function () {
        const isChecked = this.checked;
        document.querySelectorAll('input[name="vehiculos[]"]').forEach(cb => cb.checked = isChecked);
    });

    // Validar que al menos un checkbox esté marcado
    document.getElementById('formAsociarVehiculos').addEventListener('submit', function (e) {
        const seleccionados = document.querySelectorAll('input[name="vehiculos[]"]:checked');
        if (seleccionados.length === 0) {
            e.preventDefault();
            alert('Debes seleccionar al menos un vehículo para asociar.');
        }
    });
</script>
@endpush
