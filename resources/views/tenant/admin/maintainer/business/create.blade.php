<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingresar Negocio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Ingresar Nuevo Negocio</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Hay algunos problemas con tu entrada.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('empresa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name_business" class="form-label">Nombre de la Empresa</label>
            <input type="text" name="name_business" class="form-control" id="name_business" value="{{ old('name_business') }}" required>
        </div>

        <div class="mb-3">
            <label for="electronic_transfer" class="form-label">Datos de Transferencia</label>
            <textarea name="electronic_transfer" class="form-control" id="electronic_transfer" rows="3" required>{{ old('electronic_transfer') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" name="logo" class="form-control" id="logo" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Negocio</button>
        <a href="{{ route('empresa.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm
