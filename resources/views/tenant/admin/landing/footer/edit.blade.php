@extends('tenant.layouts.admin')

@section('title', 'Editar Footer')
@section('page_title', 'Editar Footer')

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header bg-secondary text-white"><i class="fas fa-shoe-prints mr-2"></i>Editar Footer</div>
    <div class="card-body">
      <form action="{{ route('landing.footer.update', $footer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label>Copyright</label>
          <input type="text" name="copyright" value="{{ $footer->copyright }}" class="form-control">
        </div>

        <div class="form-group">
          <label>Texto de contacto (separado por comas)</label>
          <input type="text" name="contact_text" value="{{ $footer->contact_text }}" class="form-control">
        </div>

        {{-- Contacto activo (con radios) --}}
        <div class="mb-3">
          <label class="form-label">Contacto activo</label>
          <div>
            <label class="me-3">
              <input type="radio" name="contact_active" value="1" {{ old('contact_active', $footer->contact_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="contact_active" value="0" {{ old('contact_active', $footer->contact_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        <div class="form-group">
          <label>Redes sociales (separado por comas)</label>
          <input type="text" name="social_text" value="{{ $footer->social_text }}" class="form-control">
        </div>

        {{-- Redes sociales activas (con radios) --}}
        <div class="mb-3">
          <label class="form-label">Redes sociales activas</label>
          <div>
            <label class="me-3">
              <input type="radio" name="social_active" value="1" {{ old('social_active', $footer->social_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="social_active" value="0" {{ old('social_active', $footer->social_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        <hr>
        <h5 class="mt-4 mb-3">Colores</h5>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label>Color de fondo</label>
            <input type="color" name="background_color" value="{{ $footer->background_color }}" class="form-control form-control-color" style="height: 50px;">
          </div>

          <div class="col-md-4 mb-3">
            <label>Color texto 1</label>
            <input type="color" name="text_color_1" value="{{ $footer->text_color_1 }}" class="form-control form-control-color" style="height: 50px;">
          </div>

          <div class="col-md-4 mb-3">
            <label>Color texto 2</label>
            <input type="color" name="text_color_2" value="{{ $footer->text_color_2 }}" class="form-control form-control-color" style="height: 50px;">
          </div>
        </div>
          <div class="d-flex justify-content-end">

            <a href="{{ route('landing.footer.index') }}" class="btn btn-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary ml-1">Actualizar</button>

          </div>

      </form>
    </div>
  </div>
</div>
@endsection
