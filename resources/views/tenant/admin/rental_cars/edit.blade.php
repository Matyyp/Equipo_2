@extends('tenant.layouts.admin')

@section('title', 'Editar Auto de Arriendo')
@section('page_title', 'Editar Auto de Arriendo')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0">Editar Auto de Arriendo</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('rental-cars.update', $rentalCar) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Marca --}}
        <div class="mb-3">
          <label for="brand_id" class="form-label">Marca</label>
          <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
            <option value="" disabled>Seleccione una marca</option>
            @foreach($brands as $id => $name)
              <option value="{{ $id }}" {{ old('brand_id', $rentalCar->brand_id) == $id ? 'selected' : '' }}>
                {{ $name }}
              </option>
            @endforeach
          </select>
          @error('brand_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Modelo --}}
        <div class="mb-3">
          <label for="model_car_id" class="form-label">Modelo</label>
          <select name="model_car_id" id="model_car_id" class="form-select @error('model_car_id') is-invalid @enderror" required>
            <option value="" disabled>Seleccione un modelo</option>
            @foreach($models as $id => $name)
              <option value="{{ $id }}" {{ old('model_car_id', $rentalCar->model_car_id) == $id ? 'selected' : '' }}>
                {{ $name }}
              </option>
            @endforeach
          </select>
          @error('model_car_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Año --}}
        <div class="mb-3">
          <label for="year" class="form-label">Año</label>
          <input
            type="number"
            name="year"
            id="year"
            class="form-control @error('year') is-invalid @enderror"
            min="1900"
            max="{{ now()->year }}"
            value="{{ old('year', $rentalCar->year) }}"
            required
          >
          @error('year')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Estado --}}
        <div class="mb-3">
          <label class="form-label">Estado</label>
          <div>
            <label class="me-3">
              <input type="radio" name="is_active" value="1" {{ old('is_active', $rentalCar->is_active) == '1' ? 'checked' : '' }}>
              Activo
            </label>
            <label>
              <input type="radio" name="is_active" value="0" {{ old('is_active', $rentalCar->is_active) == '0' ? 'checked' : '' }}>
              Inactivo
            </label>
          </div>
        </div>

        {{-- Sucursal --}}
        <div class="mb-3">
          <label for="branch_office_id" class="form-label">Sucursal</label>
          <select name="branch_office_id" id="branch_office_id"
                  class="form-select @error('branch_office_id') is-invalid @enderror" required>
            <option value="" disabled>Seleccione una sucursal</option>
            @foreach($branches as $id => $name)
              <option value="{{ $id }}"
                {{ old('branch_office_id', $rentalCar->branch_office_id) == $id ? 'selected' : '' }}>
                {{ $name }}
              </option>
            @endforeach
          </select>
          @error('branch_office_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Capacidad pasajeros --}}
        <div class="mb-3">
          <label for="passenger_capacity" class="form-label">Pasajeros</label>
          <input
            type="number"
            name="passenger_capacity"
            id="passenger_capacity"
            class="form-control @error('passenger_capacity') is-invalid @enderror"
            min="1"
            value="{{ old('passenger_capacity', $rentalCar->passenger_capacity ?? '') }}"
            required
          >
          @error('passenger_capacity')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Transmisión --}}
        <div class="mb-3">
          <label for="transmission" class="form-label">Transmisión</label>
          <select
            name="transmission"
            id="transmission"
            class="form-select @error('transmission') is-invalid @enderror"
            required
          >
            <option value="" disabled>Seleccione</option>
            <option value="manual"   {{ old('transmission', $rentalCar->transmission ?? '')=='manual'   ? 'selected':'' }}>Manual</option>
            <option value="automatic"{{ old('transmission', $rentalCar->transmission ?? '')=='automatic'?'selected':'' }}>Automática</option>
          </select>
          @error('transmission')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Capacidad maletas --}}
        <div class="mb-3">
          <label for="luggage_capacity" class="form-label">Maletas</label>
          <input
            type="number"
            name="luggage_capacity"
            id="luggage_capacity"
            class="form-control @error('luggage_capacity') is-invalid @enderror"
            min="0"
            value="{{ old('luggage_capacity', $rentalCar->luggage_capacity ?? '') }}"
            required
          >
          @error('luggage_capacity')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Precio por día --}}
        <div class="mb-3">
          <label for="price_per_day" class="form-label">Precio (por día)</label>
          <div class="input-group">
            <span class="input-group-text">$</span>
            <input
              type="number"
              name="price_per_day"
              id="price_per_day"
              class="form-control @error('price_per_day') is-invalid @enderror"
              min="0"
              step="0.01"
              value="{{ old('price_per_day', number_format($rentalCar->price_per_day ?? '', 0, ',', '.')) }}"
              required
            >
          </div>
          @error('price_per_day')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Imágenes existentes --}}
        @if($rentalCar->images->isNotEmpty())
          <div class="mb-4">
            <label class="form-label">Imágenes actuales (marca para eliminar)</label>
            <div class="row">
              @foreach($rentalCar->images as $img)
                <div class="col-auto text-center mb-3">
                  <div class="border rounded overflow-hidden mb-1" style="width:100px; height:70px;">
                    <img
                      src="{{ tenant_asset($img->path) }}"
                      alt="Imagen {{ $img->id }}"
                      class="w-100 h-100 object-cover"
                      onerror="this.onerror=null;this.src='https://via.placeholder.com/100x70?text=No+Imagen';"
                    >
                  </div>
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      name="delete_images[]"
                      value="{{ $img->id }}"
                      id="del-img-{{ $img->id }}"
                    >
                    <label class="form-check-label small" for="del-img-{{ $img->id }}">
                      Eliminar
                    </label>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        {{-- Subir nuevas imágenes --}}
        <div class="mb-3">
          <label for="images" class="form-label">Subir nuevas imágenes</label>
          <input
            type="file"
            name="images[]"
            id="images"
            class="form-control @error('images.*') is-invalid @enderror"
            accept="image/*"
            multiple
          >
          @error('images.*')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          {{-- Previsualización --}}
          <div id="image-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
        </div>

        <div class="d-flex justify-content-end">
          <a href="{{ route('rental-cars.index') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-primary ml-1">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Previsualizar nuevas imágenes
  document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    Array.from(e.target.files).forEach(file => {
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
    });
  });
</script>
@endpush
