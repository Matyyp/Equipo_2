@extends('tenant.layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Listado de usuarios</h3>

        @role('Admin')
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm float-right">
                <i class="fas fa-user-plus"></i> Nuevo
            </a>
        @endrole
    </div>

    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th><th>Email</th><th>Rol</th><th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->getRoleNames()->implode(', ') }}</td>
                    <td class="text-right">
                        @role('Admin')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endrole
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
