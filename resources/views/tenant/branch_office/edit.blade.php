<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Sucursal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Sucursal</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('sucursales.update', $branch->id_branch) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Horario</label>
            <input type="text" name="schedule" class="form-control" value="{{ old('schedule', $branch->schedule) }}" required>
        </div>

        <div class="mb-3">
            <label>Calle</label>
            <input type="text" name="street" class="form-control" value="{{ old('street', $branch->street) }}" required>
        </div>

        <div class="mb-3">
            <label>Región</label>
            <select id="region-select" class="form-select">
                <option value="">Seleccione región</option>
                @foreach ($locacion->pluck('region')->unique() as $region)
                    <option value="{{ $region }}"
                        {{ $branch->branch_office_location?->region == $region ? 'selected' : '' }}>
                        {{ $region }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Comuna</label>
            <select name="id_location" id="commune-select" class="form-select" required>
                <option value="">Seleccione comuna</option>
                @foreach ($locacion as $loc)
                    <option value="{{ $loc->id_location }}"
                        data-region="{{ $loc->region }}"
                        {{ $branch->id_location == $loc->id_location ? 'selected' : '' }}>
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
                    <option value="{{ $b->id_business }}"
                        {{ $branch->id_business == $b->id_business ? 'selected' : '' }}>
                        {{ $b->name_business }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('sucursales.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const regionSelect = document.getElementById('region-select');
        const communeSelect = document.getElementById('commune-select');

        const selectedRegion = regionSelect.value;
        Array.from(communeSelect.options).forEach(option => {
            if (!option.value) return;
            option.hidden = option.dataset.region !== selectedRegion;
        });

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
