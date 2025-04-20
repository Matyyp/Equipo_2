<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Sucursal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Registrar Nueva Sucursal</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('sucursales.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>ID de Sucursal</label>
            <input type="text" name="id_branch" class="form-control" value="{{ old('id_branch') }}" required>
        </div>

        <div class="mb-3">
            <label>Horario</label>
            <input type="text" name="schedule" class="form-control" value="{{ old('schedule') }}" required>
        </div>

        <div class="mb-3">
            <label>Calle</label>
            <input type="text" name="street" class="form-control" value="{{ old('street') }}" required>
        </div>

        <div class="mb-3">
            <label>Región</label>
            <select id="region-select" class="form-select">
                <option value="">Seleccione región</option>
                @foreach ($locacion->pluck('region')->unique() as $region)
                    <option value="{{ $region }}">{{ $region }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Comuna</label>
            <select name="id_location" id="commune-select" class="form-select" required>
                <option value="">Seleccione comuna</option>
                @foreach ($locacion as $loc)
                    <option value="{{ $loc->id_location }}" data-region="{{ $loc->region }}">
                        {{ $loc->commune }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Negocio</label>
            <select name="id_business" class="form-select" required>
                <option value="">Seleccione negocio</option>
                @foreach ($business as $b)
                    <option value="{{ $b->id_business }}">{{ $b->name_business }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('sucursales.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const regionSelect = document.getElementById('region-select');
        const communeSelect = document.getElementById('commune-select');

        regionSelect.addEventListener('change', () => {
            const region = regionSelect.value;
            Array.from(communeSelect.options).forEach(option => {
                if (!option.value) return;
                option.hidden = option.dataset.region !== region;
            });
            communeSelect.value = '';
        });
    });
</script>
</body>
</html>
