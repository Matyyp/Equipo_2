@extends('tenant.layouts.admin')

@section('title', 'Crear Mapa + Contacto')
@section('page_title', 'Crear Mapa + Contacto')

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header bg-secondary text-white"><i class="fas fa-map-marked-alt mr-2"></i>Crear Nuevo Mapa + Contacto</div>
    <div class="card-body">
      <form action="{{ route('landing.map.store') }}" method="POST">
        @csrf

        <!-- Información Principal -->
        <h5 class="mb-3">Información Principal</h5>
        
        <div class="form-group mb-4">
          <label>Título <span class="text-danger"></span></label>
          <input type="text" name="titulo" value="{{ old('titulo') }}" class="form-control" required>
          <div class="mt-2">
            <label class="d-block">Mostrar Título</label>
            <label class="me-3">
              <input type="radio" name="titulo_active" value="1" {{ old('titulo_active', '1') == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="titulo_active" value="0" {{ old('titulo_active') == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group mb-4">
              <label>Ciudad <span class="text-danger"></span></label>
              <input type="text" name="ciudad" value="{{ old('ciudad') }}" class="form-control" required>
              <div class="mt-2">
                <label class="d-block">Mostrar Ciudad</label>
                <label class="me-3">
                  <input type="radio" name="ciudad_active" value="1" {{ old('ciudad_active', '1') == '1' ? 'checked' : '' }}>
                  Sí
                </label>
                <label>
                  <input type="radio" name="ciudad_active" value="0" {{ old('ciudad_active') == '0' ? 'checked' : '' }}>
                  No
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group mb-4">
              <label>Dirección <span class="text-danger"></span></label>
              <input type="text" name="direccion" value="{{ old('direccion') }}" class="form-control" required>
              <div class="mt-2">
                <label class="d-block">Mostrar Dirección</label>
                <label class="me-3">
                  <input type="radio" name="direccion_active" value="1" {{ old('direccion_active', '1') == '1' ? 'checked' : '' }}>
                  Sí
                </label>
                <label>
                  <input type="radio" name="direccion_active" value="0" {{ old('direccion_active') == '0' ? 'checked' : '' }}>
                  No
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Contactos -->
        <div class="form-group mb-4">
          <label>Contactos (separados por comas) <span class="text-danger"></span></label>
          <textarea name="contactos" class="form-control" rows="3" required>{{ old('contactos') }}</textarea>
          <small class="text-muted">Ejemplo: +57 1234567890,contacto@empresa.com,direccion@empresa.com</small>
          <div class="mt-2">
            <label class="d-block">Mostrar Contactos</label>
            <label class="me-3">
              <input type="radio" name="contactos_active" value="1" {{ old('contactos_active', '1') == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="contactos_active" value="0" {{ old('contactos_active') == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        <!-- Horario -->
        <div class="form-group mb-4">
          <label>Horario de Atención <span class="text-danger"></span></label>
          <textarea name="horario" class="form-control" rows="3" required>{{ old('horario') }}</textarea>
          <small class="text-muted">Ejemplo: Lunes a Viernes: 8:00 AM - 6:00 PM / Sábados: 9:00 AM - 1:00 PM</small>
          <div class="mt-2">
            <label class="d-block">Mostrar Horario</label>
            <label class="me-3">
              <input type="radio" name="horario_active" value="1" {{ old('horario_active', '1') == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="horario_active" value="0" {{ old('horario_active') == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        <!-- Configuración del Mapa -->
        <hr>
        <h5 class="mt-4 mb-3">Configuración del Mapa</h5>

        <div class="form-group mb-4">
          <label>Coordenadas del Mapa <span class="text-danger"></span></label>
          <input type="text" name="coordenadas_mapa" value="{{ old('coordenadas_mapa') }}" class="form-control" required>
          <small class="text-muted">Ejemplo: 4.710989,-74.072092</small>
        </div>

        <div class="mt-2 mb-4">
          <label class="d-block">Mostrar Mapa</label>
          <label class="me-3">
            <input type="radio" name="map_active" value="1" {{ old('map_active', '1') == '1' ? 'checked' : '' }}>
            Sí
          </label>
          <label>
            <input type="radio" name="map_active" value="0" {{ old('map_active') == '0' ? 'checked' : '' }}>
            No
          </label>
        </div>

        <!-- Configuración del Botón -->
        <hr>
        <h5 class="mt-4 mb-3">Configuración del Botón</h5>

        <div class="form-group mb-4">
          <label>Texto del Botón <span class="text-danger"></span></label>
          <input type="text" name="texto_boton" value="{{ old('texto_boton') }}" class="form-control" required>
        </div>

        <div class="form-group mb-4">
          <label>URL del Botón</label>
          <input type="url" name="url_boton" value="{{ old('url_boton') }}" class="form-control">
          <small class="text-muted">Ejemplo: https://maps.google.com/?q=4.710989,-74.072092</small>
        </div>

        <div class="mt-2 mb-4">
          <label class="d-block">Mostrar Botón</label>
          <label class="me-3">
            <input type="radio" name="boton_active" value="1" {{ old('boton_active', '1') == '1' ? 'checked' : '' }}>
            Sí
          </label>
          <label>
            <input type="radio" name="boton_active" value="0" {{ old('boton_active') == '0' ? 'checked' : '' }}>
            No
          </label>
        </div>

        <!-- Configuración de Colores -->
        <hr>
        <h5 class="mt-4 mb-3">Configuración de Colores</h5>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label>Color Texto Botón</label>
            <input type="color" name="boton_color_texto" value="{{ old('boton_color_texto') }}" class="form-control form-control-color">
          </div>
          <div class="col-md-4 mb-3">
            <label>Color Fondo Botón</label>
            <input type="color" name="boton_color" value="{{ old('boton_color') }}" class="form-control form-control-color">
          </div>
          <div class="col-md-4 mb-3">
            <label>Color Tarjeta</label>
            <input type="color" name="color_tarjeta" value="{{ old('color_tarjeta') }}" class="form-control form-control-color">
          </div>
          <div class="col-md-4 mb-3">
            <label>Color Texto Tarjeta</label>
            <input type="color" name="color_texto_tarjeta" value="{{ old('color_texto_tarjeta') }}" class="form-control form-control-color">
          </div>
          <div class="col-md-4 mb-3">
            <label>Color Mapa</label>
            <input type="color" name="color_mapa" value="{{ old('color_mapa') }}" class="form-control form-control-color">
          </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
          <a href="{{ route('landing.map.index') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-primary ml-1">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
