@extends('tenant.layouts.admin')

@section('title', 'Editar Propietario')
@section('page_title', 'Editar Información del Propietario')

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
        <form action="{{ route('dueños.update', $owner->id_owner) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="type_owner">Tipo de Propietario</label>
                <select name="type_owner" id="type_owner" class="form-select" required>
                    <option value="cliente" {{ old('type_owner', $owner->type_owner) == 'cliente' ? 'selected' : '' }}>Cliente</option>
                    <option value="empresa" {{ old('type_owner', $owner->type_owner) == 'empresa' ? 'selected' : '' }}>Empresa</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="{{ old('name', $owner->name) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="last_name">Apellido</label>
                <input type="text" name="last_name" id="last_name" class="form-control"
                       value="{{ old('last_name', $owner->last_name) }}" required>
            </div>

            <div class="form-group mb-4">
                <label for="number_phone">Número de Teléfono</label>
                <input type="text" name="number_phone" id="number_phone" class="form-control"
                       value="{{ old('number_phone', $owner->number_phone) }}" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('dueños.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
