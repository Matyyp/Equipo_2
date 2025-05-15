@extends('central.layouts.app')

@section('title', 'Clientes')
@section('page_title', 'Clientes')

@section('content')
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  @endif

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Listado de Clientes</h3>
      <a href="{{ route('tenants.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle mr-1"></i> Nuevo Cliente
      </a>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <tr>
            <th>ID</th>
            <th>Dominio</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tenants as $tenant)
            <tr>
              <td>{{ $tenant->id }}</td>
              <td>{{ $tenant->domains->first()?->domain ?? '—' }}</td>
              <td class="text-center">
                <div class="btn-group btn-group-sm">
                  @if($tenant->domains->first())
                    <a href="//{{ $tenant->domains->first()->domain }}"
                       class="btn btn-success" target="_blank"
                       title="Visitar sitio">
                      <i class="fas fa-external-link-alt"></i>
                    </a>
                  @endif
                  <a href="{{ route('tenants.edit', $tenant) }}"
                     class="btn btn-secondary" title="Editar">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form action="{{ route('tenants.destroy', $tenant) }}" method="POST"
                        class="d-inline"
                        onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" title="Eliminar">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted">No hay clientes registrados.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
