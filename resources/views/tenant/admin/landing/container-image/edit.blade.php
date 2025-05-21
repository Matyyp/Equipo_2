@extends('tenant.layouts.admin')

@section('title', 'Editar Imagen')
@section('page_title', 'Editar Imagen del Contenedor')

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-edit mr-2"></i>Editar Imagen
    </div>
    <div class="card-body">
      <form action="{{ route('landing.container-image.update', $container_image) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group text-center mb-4">
          @if($container_image->path)
            <img src="{{ tenant_asset($container_image->path) }}" class="img-fluid mb-3" style="max-height: 200px;">
          @endif
        </div>

        <div class="form-group">
          <label for="image">Cambiar Imagen</label>
          <div class="custom-file">
            <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image">
            <label class="custom-file-label" for="image">Seleccionar nuevo archivo...</label>
            @error('image')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <small class="form-text text-muted">Dejar en blanco para mantener la imagen actual</small>
        </div>

        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="delete_image" name="delete_image">
          <label class="form-check-label" for="delete_image">Eliminar imagen actual</label>
        </div>

        <div class="form-group text-center mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-2"></i>Actualizar
          </button>
          <a href="{{ route('landing.container-image.index') }}" class="btn btn-secondary">
            <i class="fas fa-times mr-2"></i>Cancelar
          </a>
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
    var fileName = document.getElementById("image").files[0]?.name || "Seleccionar archivo...";
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
  });
</script>
@endpush