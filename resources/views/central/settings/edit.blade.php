@extends('central.layouts.app')

@section('title', 'Ajustes de Correo')
@section('page_title', 'Ajustes de Correo')

@section('content')
  <form action="{{ route('settings.update') }}" method="POST">
    @csrf @method('PUT')

    <div class="form-group">
      <label for="company_email">Email de la Empresa</label>
      <input type="email" name="company_email" id="company_email"
             value="{{ old('company_email', \App\Models\Setting::get('company_email')) }}"
             class="form-control" required>
    </div>

    <div class="form-group">
      <label for="company_name">Nombre de la Empresa</label>
      <input type="text" name="company_name" id="company_name"
             value="{{ old('company_name', \App\Models\Setting::get('company_name')) }}"
             class="form-control" required>
    </div>

    <button class="btn btn-primary">Guardar Cambios</button>
  </form>
@endsection
