@extends('tenant.layouts.admin')

@section('title', 'Editar usuario')
@section('page_title', 'Editar usuario')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Usuario</h3>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm float-right">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nombre</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name) }}"
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
                    value="{{ old('email', $user->email) }}"
                    required
                >
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">Rol</label>
                <select
                    name="role"
                    id="role"
                    class="form-control @error('role') is-invalid @enderror"
                >
                    @foreach(\Spatie\Permission\Models\Role::pluck('name') as $roleName)
                        <option value="{{ $roleName }}"
                            {{ old('role', $user->roles->first()?->name) === $roleName ? 'selected' : '' }}>
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
                    <i class="fas fa-save"></i> Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
