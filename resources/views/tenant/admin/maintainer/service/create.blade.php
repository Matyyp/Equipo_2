<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Nuevo Servicio</h1>

    <form action="{{ route('servicios.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nombre del Servicio</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="price_net" class="form-label">Precio Neto</label>
            <input type="number" name="price_net" class="form-control" value="{{ old('price_net') }}" required>
        </div>

        <div class="mb-3">
            <label for="type_service" class="form-label">Tipo de Servicio</label>
            <select name="type_service" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="parking" {{ old('type_service') == 'parking' ? 'selected' : '' }}>Estacionamiento</option>
                <option value="car_wash" {{ old('type_service') == 'car_wash' ? 'selected' : '' }}>Lavado de Autos</option>
                <option value="rent" {{ old('type_service') == 'rent' ? 'selected' : '' }}>Arriendo</option>
            </select>
        </div>

        <input type="hidden" name="id_branch_office" value="{{ $sucursalId }}">

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
