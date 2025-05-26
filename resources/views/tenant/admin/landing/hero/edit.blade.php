@extends('tenant.layouts.admin')

@section('title', 'Editar Hero')
@section('page_title', 'Editar Hero')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0">Editar Hero</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('landing.hero.update', $hero) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Título --}}
        <div class="mb-3">
          <label for="title" class="form-label">Título</label>
          <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $hero->title) }}" required>
          @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        {{-- Activar título --}}
        <div class="mb-3">
          <label class="mt-2 d-block">Mostrar título</label>
          <div>
            <label class="me-3">
              <input type="radio" name="title_active" value="1" {{ old('title_active', $hero->title_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="title_active" value="0" {{ old('title_active', $hero->title_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        {{-- Subtítulo --}}
        <div class="mb-3">
          <label for="subtitle" class="form-label">Subtítulo</label>
          <input type="text" name="subtitle" id="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $hero->subtitle) }}">
          @error('subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        {{-- Activar subtítulo --}}
        <div class="mb-3">
          <label class="mt-2 d-block">Mostrar subtítulo</label>
          <div>
            <label class="me-3">
              <input type="radio" name="subtitle_active" value="1" {{ old('subtitle_active', $hero->subtitle_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="subtitle_active" value="0" {{ old('subtitle_active', $hero->subtitle_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        {{-- Texto del botón --}}
        <div class="mb-3">
          <label for="button_text" class="form-label">Texto del botón</label>
          <input type="text" name="button_text" id="button_text" class="form-control" value="{{ old('button_text', $hero->button_text) }}">
        </div>
        {{-- Mostrar botón --}}
        <div class="mb-3">
          <label class="mt-2 d-block">Mostrar botón</label>
          <div>
            <label class="me-3">
              <input type="radio" name="button_active" value="1" {{ old('button_active', $hero->button_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="button_active" value="0" {{ old('button_active', $hero->button_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        {{-- URL del botón --}}
        <div class="mb-3">
          <label for="button_url" class="form-label">URL del botón</label>
          <input type="url" name="button_url" id="button_url" class="form-control" value="{{ old('button_url', $hero->button_url) }}">
        </div>
        
        <h5 class="mt-4 mb-3">Colores</h5>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="button_color" class="form-label">Color del botón</label>
            <input type="color" name="button_color" id="button_color" class="form-control form-control-color" value="{{ old('button_color', $hero->button_color ?? '#0000ff') }}">
          </div>

          {{-- Color del texto --}}
          <div class="col-md-4 mb-3">
            <label for="text_color" class="form-label">Color del texto</label>
            <input type="color" name="text_color" id="text_color" class="form-control form-control-color" value="{{ old('text_color', $hero->text_color ?? '#000000') }}">
          </div>
        </div>
        {{-- Imagen existente --}}
        @if($hero->image)
        <div class="mb-4">
          <label class="form-label">Imagen actual (marque para eliminar)</label>
          <div class="text-center mb-3">
            <div class="border rounded overflow-hidden mb-1" style="width:100px; height:70px;">
              <img src="{{ tenant_asset($hero->image->path) }}" alt="Imagen" class="w-100 h-100 object-cover" onerror="this.onerror=null;this.src='https://via.placeholder.com/100x70?text=No+Imagen';">
            </div>
            <div class="form-check">
              <input type="checkbox" name="delete_image" value="1" id="del-img" class="form-check-input" {{ old('delete_image') ? 'checked' : '' }}>
              <label for="del-img" class="form-check-label small">Eliminar</label>
            </div>
          </div>
        </div>
        @endif

        {{-- Subir nueva imagen --}}
        <div class="mb-3">
          <label for="image" class="form-label">Subir nueva imagen</label>
          <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
          @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

          <div id="image-preview" class="mt-3"></div>
        </div>

        <div class="d-flex justify-content-end">
          <a href="{{ route('landing.hero.index') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.getElementById('image').addEventListener('change', function (e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = ev => {
        const img = document.createElement('img');
        img.src = ev.target.result;
        img.classList.add('img-thumbnail');
        img.style.width = '100px';
        img.style.height = '70px';
        img.style.objectFit = 'cover';
        preview.appendChild(img);
      };
      reader.readAsDataURL(file);
    }
  });
</script>
@endpush