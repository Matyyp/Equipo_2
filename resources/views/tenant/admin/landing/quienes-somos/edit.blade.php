@extends('tenant.layouts.admin')

@section('title', 'Editar Quiénes Somos')
@section('page_title', 'Editar Quiénes Somos')

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header bg-secondary text-white"><i class="fas fa-info-circle mr-2"></i>Editar Quiénes Somos</div>
    <div class="card-body">
      <form action="{{ route('landing.quienes-somos.update', $aboutUs->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Texto Superior -->
        <div class="form-group mb-4">
          <label>Texto Superior</label>
          <textarea name="top_text" class="form-control" rows="3">{{ old('top_text', $aboutUs->top_text) }}</textarea>
          <label class="mt-2 d-block">Activar Texto Superior</label>
          <div>
            <label class="me-3">
              <input type="radio" name="top_text_active" value="1" {{ old('top_text_active', $aboutUs->top_text_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="top_text_active" value="0" {{ old('top_text_active', $aboutUs->top_text_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        <!-- Título Principal -->
        <div class="form-group mb-4">
          <label>Título Principal</label>
          <input type="text" name="main_title" value="{{ old('main_title', $aboutUs->main_title) }}" class="form-control">
          <label class="mt-2 d-block">Activar Título Principal</label>
          <div>
            <label class="me-3">
              <input type="radio" name="main_title_active" value="1" {{ old('main_title_active', $aboutUs->main_title_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="main_title_active" value="0" {{ old('main_title_active', $aboutUs->main_title_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        <!-- Texto Secundario -->
        <div class="form-group mb-4">
          <label>Texto Secundario</label>
          <textarea name="secondary_text" class="form-control" rows="3">{{ old('secondary_text', $aboutUs->secondary_text) }}</textarea>
          <label class="mt-2 d-block">Activar Texto Secundario</label>
          <div>
            <label class="me-3">
              <input type="radio" name="secondary_text_active" value="1" {{ old('secondary_text_active', $aboutUs->secondary_text_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="secondary_text_active" value="0" {{ old('secondary_text_active', $aboutUs->secondary_text_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        <!-- Texto Tercero -->
        <div class="form-group mb-4">
          <label>Texto Tercero</label>
          <textarea name="tertiary_text" class="form-control" rows="3">{{ old('tertiary_text', $aboutUs->tertiary_text) }}</textarea>
          <label class="mt-2 d-block">Activar Texto Tercero</label>
          <div>
            <label class="me-3">
              <input type="radio" name="tertiary_text_active" value="1" {{ old('tertiary_text_active', $aboutUs->tertiary_text_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="tertiary_text_active" value="0" {{ old('tertiary_text_active', $aboutUs->tertiary_text_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        <hr>
        <h5 class="mt-4 mb-3">Configuración del Botón</h5>

        <div class="form-group mb-4">
          <label>Texto del Botón</label>
          <input type="text" name="button_text" value="{{ old('button_text', $aboutUs->button_text) }}" class="form-control">
        </div>

        <div class="form-group mb-4">
          <label>Enlace del Botón</label>
          <input type="text" name="button_link" value="{{ old('button_link', $aboutUs->button_link) }}" class="form-control">
        </div>

        <div class="mb-4">
          <label class="mt-2 d-block">Activar Botón</label>
          <div>
            <label class="me-3">
              <input type="radio" name="button_active" value="1" {{ old('button_active', $aboutUs->button_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="button_active" value="0" {{ old('button_active', $aboutUs->button_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        <hr>
        <h5 class="mt-4 mb-3">Configuración de Colores</h5>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label>Color Texto Botón</label>
            <input type="color" name="button_text_color" value="{{ old('button_text_color', $aboutUs->button_text_color) }}" class="form-control form-control-color">
          </div>
          <div class="col-md-4 mb-3">
            <label>Color Botón</label>
            <input type="color" name="button_color" value="{{ old('button_color', $aboutUs->button_color) }}" class="form-control form-control-color">
          </div>
          <div class="col-md-4 mb-3">
            <label>Color Tarjeta</label>
            <input type="color" name="card_color" value="{{ old('card_color', $aboutUs->card_color) }}" class="form-control form-control-color">
          </div>
          <div class="col-md-4 mb-3">
            <label>Color Texto Tarjeta</label>
            <input type="color" name="card_text_color" value="{{ old('card_text_color', $aboutUs->card_text_color) }}" class="form-control form-control-color">
          </div>
          <div class="col-md-4 mb-3">
            <label>Color Tarjeta Video</label>
            <input type="color" name="video_card_color" value="{{ old('video_card_color', $aboutUs->video_card_color) }}" class="form-control form-control-color">
          </div>
        </div>

        <hr>
        <h5 class="mt-4 mb-3">Configuración de Videos</h5>

        <div class="form-group mb-4">
          <label>Links de Video (separados por coma)</label>
          <textarea name="video_links" class="form-control" rows="3">{{ old('video_links', $aboutUs->video_links) }}</textarea>
          <small class="text-muted">Ejemplo: https://youtube.com/embed/ejemplo1,https://youtube.com/embed/ejemplo2</small>
        </div>

        <div class="d-flex justify-content-end">
          <a href="{{ route('landing.quienes-somos.index') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-primary ml-1">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection