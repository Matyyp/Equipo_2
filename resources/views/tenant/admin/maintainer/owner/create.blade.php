@extends('tenant.layouts.admin')

@section('title', 'Registrar Propietario')
@section('page_title', 'Registrar Nuevo Propietario')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Validación de errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Por favor revisa los siguientes errores:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario de creación --}}
        <form action="{{ route('dueños.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="type_owner">Tipo de Propietario</label>
                <select name="type_owner" id="type_owner" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="cliente" {{ old('type_owner') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                    <option value="empresa" {{ old('type_owner') == 'empresa' ? 'selected' : '' }}>Empresa</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="{{ old('name') }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="last_name">Apellido</label>
                <input type="text" name="last_name" id="last_name" class="form-control"
                       value="{{ old('last_name') }}" required>
            </div>

            <div class="form-group mb-4">
                <label for="number_phone">Número de Teléfono</label>
                <input type="text" name="number_phone" id="number_phone" class="form-control"
                       value="{{ old('number_phone') }}" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('dueños.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
