@extends('tenant.layouts.admin')

@section('title', 'Editar Negocio')
@section('page_title', 'Editar Información del Negocio')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Validación de errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Hubo algunos errores con tus datos.<br><br>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario de edición --}}
        <form action="{{ route('empresa.update', $business->id_business) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="name_business">Nombre de la Empresa</label>
                <input type="text" id="name_business" name="name_business" class="form-control"
                       value="{{ old('name_business', $business->name_business) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="electronic_transfer">Datos de Transferencia</label>
                <input type="text" id="electronic_transfer" name="electronic_transfer" class="form-control"
                       value="{{ old('electronic_transfer', $business->electronic_transfer) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="logo">Logo (opcional)</label>
                <input type="file" id="logo" name="logo" class="form-control" accept="image/*">

                @if ($business->logo)
                    <p class="mt-2 mb-1">Logo actual:</p>
                    <img src="/storage/tenants/{{ request()->getHost() }}/imagenes/{{ $business->logo }}"
                                    alt="Logo del Negocio" class="img-thumbnail" width="100">
                @endif
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Empresa
                </button>
                <a href="{{ route('empresa.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
