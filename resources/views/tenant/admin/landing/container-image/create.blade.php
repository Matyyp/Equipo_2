@extends('tenant.layouts.admin')

@section('title', 'Agregar Imagen')
@section('page_title', 'Agregar Imagen al Contenedor')

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-images mr-2"></i>Nueva Imagen
    </div>
    <div class="card-body">
      <form action="{{ route('landing.container-image.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
          <label for="image">Imagen</label>
          <div class="custom-file">
            <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" required>
            <label class="custom-file-label" for="image">Seleccionar archivo...</label>
            @error('image')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <small class="form-text text-muted">Formatos soportados: JPG, PNG. Tamaño máximo: 2MB</small>
        </div>

        <div class="form-group text-right mt-4">
          <a href="{{ route('landing.container-image.index') }}" class="btn btn-secondary">
            Cancelar
          </a>
          <button type="submit" class="btn btn-primary">
            Guardar
          </button>

        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Muestra el nombre del archivo seleccionado
  document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = document.getElementById("image").files[0].name;
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
  });
</script>
@endpush