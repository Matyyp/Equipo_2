@extends('tenant.layouts.admin')

@section('title', 'Editar Servicio')
@section('page_title', 'Editar Servicio')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0">Editar Servicio</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('landing.service.update', $serviceLanding) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Título --}}
        <div class="mb-3">
          <label for="title" class="form-label">Título</label>
          <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $serviceLanding->title) }}" required>
          @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Activar título --}}
        <div class="mb-3">
          <label class="mt-2 d-block">Mostrar título</label>
          <div>
            <label class="me-3">
              <input type="radio" name="title_active" value="1" {{ old('title_active', $serviceLanding->title_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="title_active" value="0" {{ old('title_active', $serviceLanding->title_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        {{-- Texto Secundario --}}
        <div class="mb-3">
          <label for="secondary_text" class="form-label">Texto Secundario</label>
          <textarea name="secondary_text" id="secondary_text" class="form-control @error('secondary_text') is-invalid @enderror">{{ old('secondary_text', $serviceLanding->secondary_text) }}</textarea>
          @error('secondary_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Activar texto secundario --}}
        <div class="mb-3">
          <label class="mt-2 d-block">Mostrar texto secundario</label>
          <div>
            <label class="me-3">
              <input type="radio" name="secondary_text_active" value="1" {{ old('secondary_text_active', $serviceLanding->secondary_text_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="secondary_text_active" value="0" {{ old('secondary_text_active', $serviceLanding->secondary_text_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        {{-- Small Text --}}
        <div class="mb-3">
          <label for="small_text" class="form-label">Texto Pequeño</label>
          <input type="text" name="small_text" id="small_text" class="form-control" value="{{ old('small_text', $serviceLanding->small_text) }}">
        </div>

        {{-- Activar small text --}}
        <div class="mb-3">
          <label class="mt-2 d-block">Mostrar texto pequeño</label>
          <div>
            <label class="me-3">
              <input type="radio" name="small_text_active" value="1" {{ old('small_text_active', $serviceLanding->small_text_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="small_text_active" value="0" {{ old('small_text_active', $serviceLanding->small_text_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        {{-- Color del título --}}
        <div class="mb-3">
          <label for="title_color" class="form-label">Color del título</label>
          <input type="color" name="title_color" id="title_color" class="form-control form-control-color" value="{{ old('title_color', $serviceLanding->title_color ?? '#000000') }}">
        </div>

        {{-- Color del texto secundario --}}
        <div class="mb-3">
          <label for="secondary_text_color" class="form-label">Color del texto secundario</label>
          <input type="color" name="secondary_text_color" id="secondary_text_color" class="form-control form-control-color" value="{{ old('secondary_text_color', $serviceLanding->secondary_text_color ?? '#000000') }}">
        </div>

        {{-- Color del small text --}}
        <div class="mb-3">
          <label for="small_text_color" class="form-label">Color del texto pequeño</label>
          <input type="color" name="small_text_color" id="small_text_color" class="form-control form-control-color" value="{{ old('small_text_color', $serviceLanding->small_text_color ?? '#000000') }}">
        </div>

        {{-- Color de fondo --}}
        <div class="mb-3">
          <label for="card_background_color" class="form-label">Color de fondo</label>
          <input type="color" name="card_background_color" id="card_background_color" class="form-control form-control-color" value="{{ old('card_background_color', $serviceLanding->card_background_color ?? '#ffffff') }}">
        </div>

        {{-- Imagen existente --}}
        @if($serviceLanding->image)
        <div class="mb-4">
          <label class="form-label">Imagen actual (marque para eliminar)</label>
          <div class="text-center mb-3">
            <div class="border rounded overflow-hidden mb-1" style="width:100px; height:70px;">
              <img src="{{ tenant_asset($serviceLanding->image->path) }}" alt="Imagen" class="w-100 h-100 object-cover" onerror="this.onerror=null;this.src='https://via.placeholder.com/100x70?text=No+Imagen';">
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
          <a href="{{ route('landing.service.index') }}" class="btn btn-secondary me-2">Cancelar</a>
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