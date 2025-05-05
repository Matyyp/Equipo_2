@extends('tenant.layouts.admin')

@section('title', 'Crear Regla')
@section('page_title', 'Registrar Nueva Regla')

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
        <form action="{{ route('reglas.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="name">Nombre de la Regla</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="{{ old('name') }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Descripción</label>
                <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
            </div>

            <div class="form-group mb-4">
                <label for="type_contract">Tipo de Contrato</label>
                <select name="type_contract" id="type_contract" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <option value="rent" {{ old('type_contract') == 'rent' ? 'selected' : '' }}>Renta</option>
                    <option value="parking_daily" {{ old('type_contract') == 'parking_daily' ? 'selected' : '' }}>Estacionamiento Diario</option>
                    <option value="parking_annual" {{ old('type_contract') == 'parking_annual' ? 'selected' : '' }}>Estacionamiento Anual</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('reglas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
