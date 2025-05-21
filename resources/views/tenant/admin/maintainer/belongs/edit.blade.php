@extends('tenant.layouts.admin')

@section('title', 'Asociar Vehículos')
@section('page_title', 'Asociar Vehículos al Propietario')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-car mr-2"></i>Asociar Vehículos al Propietario {{ $name }}</div>
    </div>

    <form action="{{ route('asociado.store') }}" method="POST" id="formAsociarVehiculos">
      @csrf
      <input type="hidden" name="id_owner" value="{{ $id }}">

      <div class="card-body">
        {{-- Campo de búsqueda --}}
        <div class="form-group mb-3">
          <input type="text" id="searchInput" class="form-control" placeholder="Buscar por patente...">
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="vehiculosTable">
            <thead class="thead-light">
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
                  <td class="placa">{{ $car['patent'] ?? '-' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted">No hay vehículos disponibles para asociar.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
        <div class="card-footer d-flex justify-content-end flex-wrap">
          <a href="{{ route('dueños.index') }}" class="btn btn-outline-secondary mr-2">
            <i class="fas fa-arrow-left mr-1"></i> Volver
          </a>

          <button type="submit" id="submit-btn" class="btn btn-outline-success mr-2">
            <i class="fas fa-plus mr-1"></i> Asociar Vehículos Seleccionados
          </button>
        </div>
    </form>
  </div>

</div>
@endsection

@push('scripts')
<script>
  // Filtro por patente
  document.getElementById('searchInput').addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#vehiculosTable tbody tr');

    let visible = 0;
    rows.forEach(row => {
      const placa = row.querySelector('.placa')?.textContent.toLowerCase() || '';
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

  // Selección múltiple
  document.getElementById('selectAll').addEventListener('change', function () {
    const isChecked = this.checked;
    document.querySelectorAll('input[name="vehiculos[]"]').forEach(cb => cb.checked = isChecked);
  });

  // Validación al enviar
  document.getElementById('formAsociarVehiculos').addEventListener('submit', function (e) {
    const seleccionados = document.querySelectorAll('input[name="vehiculos[]"]:checked');
    if (seleccionados.length === 0) {
      e.preventDefault();
      alert('Debes seleccionar al menos un vehículo para asociar.');
    }
  });
</script>
@endpush
