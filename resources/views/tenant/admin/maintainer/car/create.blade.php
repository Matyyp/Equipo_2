<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Auto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Registrar Auto</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('autos.store') }}" method="POST">
        @csrf

        {{-- Patente --}}
        <div class="mb-3">
            <label for="patent" class="form-label">Patente</label>
            <input type="text" name="patent" id="patent" class="form-control" value="{{ old('patent') }}" required>
        </div>

        {{-- Marca --}}
        <div class="mb-3">
            <label for="brand_id" class="form-label">Marca</label>
            <select name="brand_id" id="brand_id" class="form-select select2" required>
                <option value="">Seleccione una marca</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id_brand }}">{{ $brand->name_brand }}</option>
                @endforeach
            </select>
        </div>

        {{-- Modelo --}}
        <div class="mb-3">
            <label for="model_id" class="form-label">Modelo</label>
            <select name="model_id" id="model_id" class="form-select select2" required>
                <option value="">Seleccione un modelo</option>
                @foreach($models as $model)
                    <option value="{{ $model->id_model }}">{{ $model->name_model }}</option>
                @endforeach
            </select>
        </div>

        {{-- ¿Es para arriendo? --}}
        <div class="mb-3">
            <label class="form-label">¿Está disponible para arriendo?</label>
            <select name="is_rentable" id="is_rentable" class="form-select" required>
                <option value="">Seleccione una opción</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        {{-- Valor Arriendo (solo si es para arriendo) --}}
        <div class="mb-3 d-none" id="rent_value_group">
            <label for="value_rent" class="form-label">Valor Arriendo</label>
            <input type="number" name="value_rent" id="value_rent" class="form-control" min="0">
        </div>

        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('autos.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            width: '100%',
            allowClear: true,
        });

        // Mostrar u ocultar el campo de arriendo
        $('#is_rentable').on('change', function () {
            if ($(this).val() === "1") {
                $('#rent_value_group').removeClass('d-none');
                $('#value_rent').attr('required', true);
            } else {
                $('#rent_value_group').addClass('d-none');
                $('#value_rent').val('').removeAttr('required');
            }
        });
    });
</script>
</body>
</html>
