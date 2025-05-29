@extends('central.layouts.app')

@section('title', 'Crear Cliente')
@section('page_title', 'Crear Cliente')

@section('content')
<div class="container-fluid">
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-left: 4px solid #dc3545 !important;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle mr-2 fa-lg"></i>
            <div>
                <h6 class="font-weight-bold mb-1">Error en el formulario</h6>
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header py-3" style="background: linear-gradient(135deg, rgba(30,60,114,0.05) 0%, rgba(42,82,152,0.03) 100%); border-bottom: 1px solid rgba(0,0,0,0.05);">
            <h3 class="card-title mb-0" style="color: #1e3c72; font-weight: 600;">
                <i class="fas fa-plus-circle mr-2" style="color: #2a5298;"></i>Nuevo Cliente
            </h3>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('tenants.store') }}">
                @csrf

                <div class="form-group">
                    <label for="id" class="font-weight-bold" style="color: #1e3c72;">ID del Cliente</label>
                    <input type="text"
                           name="id"
                           id="id"
                           value="{{ old('id') }}"
                           class="form-control shadow-sm"
                           style="border-radius: 8px; border: 1px solid rgba(30,60,114,0.1);"
                           required>
                    <small class="form-text text-muted">Identificador único para el cliente</small>
                </div>

                <div class="form-group mt-4">
                    <label for="domain" class="font-weight-bold" style="color: #1e3c72;">Dominio</label>
                    <input type="text"
                           name="domain"
                           id="domain"
                           value="{{ old('domain') }}"
                           class="form-control shadow-sm"
                           style="border-radius: 8px; border: 1px solid rgba(30,60,114,0.1);"
                           placeholder="ejemplo.dominio.com"
                           required>
                    <small class="form-text text-muted">Ingrese el dominio completo</small>
                </div>

                <div class="form-group mt-4">
                    <label for="email" class="font-weight-bold" style="color: #1e3c72;">Email del Administrador</label>
                    <input type="email" 
                           name="email" 
                           id="email"
                           value="{{ old('email') }}"
                           class="form-control shadow-sm"
                           style="border-radius: 8px; border: 1px solid rgba(30,60,114,0.1);"
                           required>
                    <small class="form-text text-muted">Se enviarán las credenciales a este email</small>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    <a href="{{ route('tenants.index') }}" class="btn btn-light shadow-sm" style="border-radius: 8px; border: 1px solid rgba(0,0,0,0.1);">
                        <i class="fas fa-arrow-left mr-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary shadow-sm" style="border-radius: 8px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none;">
                        <i class="fas fa-save mr-1"></i> Crear Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-control {
        transition: all 0.3s;
        padding: 10px 15px;
    }
    
    .form-control:focus {
        border-color: rgba(30,60,114,0.3);
        box-shadow: 0 0 0 0.2rem rgba(30,60,114,0.1);
    }
    
    .btn-light:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush