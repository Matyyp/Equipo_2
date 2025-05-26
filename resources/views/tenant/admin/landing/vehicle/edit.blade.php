@extends('tenant.layouts.admin')

@section('title', 'Editar Tipo de Vehículo')
@section('page_title', 'Editar Tipo de Vehículo')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0">Editar Tipo de Vehículo</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('landing.vehicle.update', $vehicle) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Título --}}
        <div class="mb-3">
          <label for="card_title" class="form-label">Título</label>
          <input type="text" name="card_title" id="card_title" class="form-control @error('card_title') is-invalid @enderror" value="{{ old('card_title', $vehicle->card_title) }}" required>
          @error('card_title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Activar título --}}
        <div class="mb-3">
          <label class="mt-2 d-block">Mostrar título</label>
          <div>
            <label class="me-3">
              <input type="radio" name="card_title_active" value="1" {{ old('card_title_active', $vehicle->card_title_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="card_title_active" value="0" {{ old('card_title_active', $vehicle->card_title_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        {{-- Subtítulo --}}
        <div class="mb-3">
          <label for="card_subtitle" class="form-label">Subtítulo</label>
          <input type="text" name="card_subtitle" id="card_subtitle" class="form-control @error('card_subtitle') is-invalid @enderror" value="{{ old('card_subtitle', $vehicle->card_subtitle) }}">
          @error('card_subtitle')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Activar subtítulo --}}
        <div class="mb-3">
          <label class="mt-2 d-block">Mostrar subtítulo</label>
          <div>
            <label class="me-3">
              <input type="radio" name="card_subtitle_active" value="1" {{ old('card_subtitle_active', $vehicle->card_subtitle_active) == '1' ? 'checked' : '' }}>
              Sí
            </label>
            <label>
              <input type="radio" name="card_subtitle_active" value="0" {{ old('card_subtitle_active', $vehicle->card_subtitle_active) == '0' ? 'checked' : '' }}>
              No
            </label>
          </div>
        </div>

        {{-- Colores --}}
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="text_color" class="form-label">Color del Texto</label>
            <input type="color" name="text_color" id="text_color" class="form-control form-control-color" value="{{ old('text_color', $vehicle->text_color ?? '#000000') }}">
          </div>
          <div class="col-md-6 mb-3">
            <label for="card_background_color" class="form-label">Color de Fondo de la Tarjeta</label>
            <input type="color" name="card_background_color" id="card_background_color" class="form-control form-control-color" value="{{ old('card_background_color', $vehicle->card_background_color ?? '#f8f9fa') }}">
          </div>
        </div>

        {{-- Imagen existente --}}
        @if($vehicle->image)
        <div class="mb-4">
          <label class="form-label">Imagen actual (marque para eliminar)</label>
          <div class="text-center mb-3">
            <div class="border rounded overflow-hidden mb-1" style="width:100px; height:70px;">
              <img src="{{ tenant_asset($vehicle->image->path) }}" alt="Imagen" class="w-100 h-100 object-cover" onerror="this.onerror=null;this.src='https://via.placeholder.com/100x70?text=No+Imagen';">
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
          @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <div id="image-preview" class="mt-3"></div>
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-end">
          <a href="{{ route('landing.vehicle.index') }}" class="btn btn-secondary me-2">Cancelar</a>
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
