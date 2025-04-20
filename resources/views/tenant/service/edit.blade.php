<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Editar Servicio</h1>

    <form action="{{ route('servicios.update', $service->id_service) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nombre del Servicio</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $service->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="price_net" class="form-label">Precio Neto</label>
            <input type="number" name="price_net" class="form-control" value="{{ old('price_net', $service->price_net) }}" required>
        </div>

        <div class="mb-3">
            <label for="type_service" class="form-label">Tipo de Servicio</label>
            <select name="type_service" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="parking" {{ $service->type_service == 'parking' ? 'selected' : '' }}>Estacionamiento</option>
                <option value="car_wash" {{ $service->type_service == 'car_wash' ? 'selected' : '' }}>Lavado de Autos</option>
                <option value="rent" {{ $service->type_service == 'rent' ? 'selected' : '' }}>Arriendo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
