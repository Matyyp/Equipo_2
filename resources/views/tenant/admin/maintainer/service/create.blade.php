@extends('tenant.layouts.admin')

@section('title', 'Crear Servicio')
@section('page_title', 'Registrar Nuevo Servicio')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Validación de errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Revisa los siguientes errores:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario --}}
        <form action="{{ route('servicios.store') }}" method="POST">
            @csrf

            {{-- Campo oculto con ID de sucursal --}}
            <input type="hidden" name="id_branch_office" value="{{ $sucursalId }}">

            <div class="form-group mb-3">
                <label for="name">Nombre del Servicio</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="{{ old('name') }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="price_net">Precio Neto</label>
                <input type="number" name="price_net" id="price_net" class="form-control"
                       value="{{ old('price_net') }}" required>
            </div>

            <div class="form-group mb-4">
                <label for="type_service">Tipo de Servicio</label>
                <select name="type_service" id="type_service" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="parking" {{ old('type_service') == 'parking' ? 'selected' : '' }}>Estacionamiento</option>
                    <option value="car_wash" {{ old('type_service') == 'car_wash' ? 'selected' : '' }}>Lavado de Autos</option>
                    <option value="rent" {{ old('type_service') == 'rent' ? 'selected' : '' }}>Arriendo</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('servicios.show', $sucursalId) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
