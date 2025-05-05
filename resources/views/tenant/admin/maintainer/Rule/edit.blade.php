@extends('tenant.layouts.admin')

@section('title', 'Editar Regla')
@section('page_title', 'Editar Regla del Sistema')

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
        <form action="{{ route('reglas.update', $rule->id_rule) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="name">Nombre de la Regla</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="{{ old('name', $rule->name) }}" required>
            </div>

            <div class="form-group mb-4">
                <label for="description">Descripción</label>
                <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description', $rule->description) }}</textarea>
            </div>

            <div class="form-group mb-4">
                <label>Tipo de Contrato Asociado</label>
                <input type="text" class="form-control bg-light" value="{{ strtoupper(str_replace('_', ' ', $rule->type_contract)) }}" readonly>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('reglas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
