@extends('tenant.layouts.admin')

@section('title', 'Editar Navbar')
@section('page_title', 'Editar Navbar')

@section('content')
<class="container">
  <div class="card">
    
    <div class="card-header bg-secondary text-white"><i class="fas fa-bars mr-2"></i>Editar Navbar</div>
    <div class="card-body">
      <form action="{{ route('landing.navbar.update', $navbar) }}" method="POST">
        @csrf
        @method('PUT')

          @php
            $labels = [
              'reservations' => 'Reservaciones',
              'schedule' => 'Horario',
              'email' => 'Correo electrónico',
              'address' => 'Dirección',
              'services' => 'Servicios',
              'about_us' => 'Sobre nosotros',
              'contact_us' => 'Contáctanos',
            ];
          @endphp

          @foreach(['reservations', 'schedule', 'email', 'address', 'services', 'about_us', 'contact_us'] as $field)
            <div class="form-group mb-4">
              <label>{{ $labels[$field] }}</label>
              <input type="text" name="{{ $field }}" value="{{ $navbar->$field }}" class="form-control">

              @if ($field === 'services')
                <small class="text-muted d-block mt-1">
                  Usa las opciones: <strong>Arrienda tu auto</strong> y/o <strong>Agenda estacionamiento</strong>, separadas por coma.
                </small>
              @endif

              <label class="mt-2 d-block">{{ $labels[$field] }} activo</label>
              <div>
                <label class="me-3">
                  <input type="radio" name="{{ $field . '_active' }}" value="1" {{ old($field . '_active', $navbar[$field . '_active']) == '1' ? 'checked' : '' }}>
                  Sí
                </label>
                <label>
                  <input type="radio" name="{{ $field . '_active' }}" value="0" {{ old($field . '_active', $navbar[$field . '_active']) == '0' ? 'checked' : '' }}>
                  No
                </label>
              </div>
            </div>
          @endforeach


        <hr>
        <h5 class="mt-4 mb-3">Colores</h5>
        <div class="row">
          @foreach([
            'background_color_1' => 'Color de fondo 1',
            'background_color_2' => 'Color de fondo 2',
            'button_color_1' => 'Color botón 1',
            'button_color_2' => 'Color botón 2',
            'text_color_1' => 'Color texto 1',
            'text_color_2' => 'Color texto 2'
          ] as $color => $label)
            <div class="col-md-4 mb-3">
              <label>{{ $label }}</label>
              <input type="color" name="{{ $color }}" value="{{ $navbar->$color }}" class="form-control form-control-color" style="height: 50px;">
            </div>
          @endforeach
        </div>

        <hr>
        <h5 class="mt-4 mb-3">Botones</h5>

        <div class="form-group mb-4">
          <label>Botón 1</label>
          <input type="text" name="button_1" value="{{ $navbar->button_1 }}" class="form-control">

          <label class="mt-2 d-block">Activo</label>
          <div>
            <label class="me-3">
              <input type="radio" name="button_1_active" value="1" {{ old('button_1_active', $navbar->button_1_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="button_1_active" value="0" {{ old('button_1_active', $navbar->button_1_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        <div class="form-group mb-4">
          <label>Botón 2</label>
          <input type="text" name="button_2" value="{{ $navbar->button_2 }}" class="form-control">

          <label class="mt-2 d-block">Activo</label>
          <div>
            <label class="me-3">
              <input type="radio" name="button_2_active" value="1" {{ old('button_2_active', $navbar->button_2_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="button_2_active" value="0" {{ old('button_2_active', $navbar->button_2_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>
        <div class="d-flex justify-content-end">
          <a href="{{ route('landing.navbar.index') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-primary ml-1">Actualizar</button>

        </div>    
      </form>
    </div>
  </div>
</div>
@endsection
