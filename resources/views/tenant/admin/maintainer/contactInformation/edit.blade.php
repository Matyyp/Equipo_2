@extends('tenant.layouts.admin')

@section('title', 'Editar Contacto')
@section('page_title', 'Editar Información de Contacto')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Validación de errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Por favor corrige los siguientes errores:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario --}}
        <form action="{{ route('informacion_contacto.update', $data->id_contact_information) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="id_branch_office" value="{{ $data->id_branch_office }}">

            <div class="form-group mb-3">
                <label for="type_contact">Tipo de Contacto</label>
                <select name="type_contact" id="type_contact" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <option value="email" {{ old('type_contact', $data->type_contact) == 'email' ? 'selected' : '' }}>Correo Electrónico</option>
                    <option value="phone" {{ old('type_contact', $data->type_contact) == 'phone' ? 'selected' : '' }}>Teléfono</option>
                    <option value="mobile" {{ old('type_contact', $data->type_contact) == 'mobile' ? 'selected' : '' }}>Celular</option>
                    <option value="whatsapp" {{ old('type_contact', $data->type_contact) == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                    <option value="website" {{ old('type_contact', $data->type_contact) == 'website' ? 'selected' : '' }}>Sitio Web</option>
                    <option value="social" {{ old('type_contact', $data->type_contact) == 'social' ? 'selected' : '' }}>Red Social</option>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="data_contact">Dato de Contacto</label>
                <input type="text" name="data_contact" id="data_contact" class="form-control"
                       value="{{ old('data_contact', $data->data_contact) }}" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('informacion_contacto.show', $data->id_branch_office) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
