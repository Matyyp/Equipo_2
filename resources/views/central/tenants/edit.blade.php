@extends('central.layouts.app')

@section('title', 'Editar Cliente')
@section('page_title', 'Editar Cliente')

@section('content')
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Editar Cliente #{{ $tenant->id }}</h3>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('tenants.update', $tenant) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label for="id">ID del Cliente</label>
          <input type="text"
                 id="id"
                 value="{{ $tenant->id }}"
                 class="form-control"
                 disabled>
        </div>

        <div class="form-group">
          <label for="domain">Dominio</label>
          <input type="text"
                 name="domain"
                 id="domain"
                 value="{{ old('domain', $tenant->domains->first()?->domain) }}"
                 class="form-control"
                 required>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save mr-1"></i> Guardar cambios
        </button>
        <a href="{{ route('tenants.index') }}" class="btn btn-secondary ml-2">
          Cancelar
        </a>
      </form>
    </div>
  </div>
@endsection
