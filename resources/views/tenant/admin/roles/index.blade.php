@extends('tenant.layouts.admin')
@section('title','Roles')
@section('page_title','Roles del sistema')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Listado de Roles</h3>
    @role('Admin')
        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm float-right">
            <i class="fas fa-user-plus"></i> Nuevo
        </a>
    @endrole
  </div>
  <div class="card-body p-0">
    <table class="table">
      <thead>
        <tr><th>Rol</th><th>Permisos</th><th>Acciones</th></tr>
      </thead>
      <tbody>
      @foreach($roles as $role)
        <tr>
          <td>{{ $role->name }}</td>
          <td>{{ $role->permissions_count }}</td>
          <td>
            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning">
              <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar rol {{ $role->name }}?')">
                  <i class="fas fa-trash"></i>
              </button>
          </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
