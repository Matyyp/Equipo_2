@extends('tenant.layouts.admin')

@section('title', 'Crear Hero')
@section('page_title', 'Crear Hero')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0"><i class="fas fa-photo-video mr-2"></i>Nuevo Hero</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('landing.hero.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Título --}}
        <div class="mb-3">
          <label for="title" class="form-label">Título</label>
          <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
          @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Estado título --}}
        <div class="mb-3">
          <label class="mt-2 d-block">Mostrar título</label>
          <div>
            <label class="me-3">
              <input type="radio" name="title_active" value="1" {{ old('title_active', '1') == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="title_active" value="0" {{ old('title_active') == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        {{-- Subtítulo --}}
        <div class="mb-3">
          <label for="subtitle" class="form-label">Subtítulo</label>
          <input type="text" name="subtitle" id="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle') }}">
          @error('subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Estado subtítulo --}}
        <div class="mb-3">
          <label class="mt-2 d-block">Mostrar subtítulo</label>
          <div>
            <label class="me-3">
              <input type="radio" name="subtitle_active" value="1" {{ old('subtitle_active', '1') == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="subtitle_active" value="0" {{ old('subtitle_active') == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        {{-- Texto botón --}}
        <div class="mb-3">
          <label for="button_text" class="form-label">Texto botón</label>
          <input type="text" name="button_text" id="button_text" class="form-control @error('button_text') is-invalid @enderror" value="{{ old('button_text') }}">
          @error('button_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Estado botón --}}
        <div class="mb-3">
          <label class="mt-2 d-block">Mostrar botón</label>
          <div>
            <label class="me-3">
              <input type="radio" name="button_active" value="1" {{ old('button_active', '1') == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="button_active" value="0" {{ old('button_active') == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        {{-- URL botón --}}
        <div class="mb-3">
          <label for="button_url" class="form-label">URL botón</label>
          <input type="url" name="button_url" id="button_url" class="form-control @error('button_url') is-invalid @enderror" value="{{ old('button_url') }}">
          @error('button_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Color botón --}}
        <div class="mb-3">
          <label for="button_color" class="form-label">Color botón</label>
          <input type="color" name="button_color" id="button_color" class="form-control form-control-color" value="{{ old('button_color', '#000000') }}" required>
        </div>

        {{-- Color texto --}}
        <div class="mb-3">
          <label for="text_color" class="form-label">Color texto</label>
          <input type="color" name="text_color" id="text_color" class="form-control form-control-color" value="{{ old('text_color', '#000000') }}" required>
        </div>

        {{-- Imagen (solo 1) --}}
        <div class="mb-3">
          <label for="image" class="form-label">Imagen</label>
          <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
          @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

          <div id="image-preview" class="mt-3"></div>
        </div>

        <div class="d-flex justify-content-end">
          <a href="{{ route('landing.hero.index') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-primary ml-1">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.getElementById('image').addEventListener('change', function (event) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = e => {
      const img = document.createElement('img');
      img.src = e.target.result;
      img.classList.add('img-thumbnail');
      img.style.width = '200px';
      preview.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
</script>
@endpush
