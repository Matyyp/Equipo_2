@extends('central.layouts.app')

@section('title', 'Crear Cliente')
@section('page_title', 'Crear Cliente')

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
      <h3 class="card-title">Nuevo Cliente</h3>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('tenants.store') }}">
        @csrf

        <div class="form-group">
          <label for="id">ID del Cliente</label>
          <input type="text"
                 name="id"
                 id="id"
                 value="{{ old('id') }}"
                 class="form-control"
                 required>
        </div>

        <div class="form-group">
          <label for="domain">Dominio</label>
          <input type="text"
                 name="domain"
                 id="domain"
                 value="{{ old('domain') }}"
                 class="form-control"
                 required>
        </div>

        <div class="form-group">
          <label for="email">Email del Administrador</label>
          <input type="email" name="email" id="email"
                value="{{ old('email') }}"
                class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save mr-1"></i> Crear
        </button>
        <a href="{{ route('tenants.index') }}" class="btn btn-secondary ml-2">
          Cancelar
        </a>
      </form>
    </div>
  </div>
@endsection
