@extends('tenant.layouts.admin')

@section('title', 'Nuevo usuario')
@section('page_title', 'Nuevo usuario')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Crear Nuevo Usuario</h3>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm float-right">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nombre</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" 
                    required
                >
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email') }}" 
                    required
                >
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    required
                >
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="form-control" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="role">Rol</label>
                <select 
                    name="role" 
                    id="role" 
                    class="form-control @error('role') is-invalid @enderror"
                >
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Selecciona un rol --</option>
                    @foreach(\Spatie\Permission\Models\Role::pluck('name') as $roleName)
                        <option value="{{ $roleName }}"
                            {{ old('role') === $roleName ? 'selected' : '' }}>
                            {{ $roleName }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
