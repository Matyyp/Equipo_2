@extends('tenant.layouts.admin')

@section('title', 'Editar vehículo de Arriendo')
@section('page_title', 'Editar vehículo de Arriendo')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
  .img-thumbnail {
    width: 100px;
    height: 70px;
    object-fit: cover;
  }
  .alert {
    padding: 0.5rem 1rem;
    margin-bottom: 1rem;
  }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-car mr-2"></i>
      <h5 class="mb-0">Editar Vehículo de Arriendo</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('rental-cars.update', $rentalCar) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
          {{-- Columna Izquierda --}}
          <div class="col-md-6">
            {{-- Marca --}}
            <div class="form-group mb-3">
              <label for="brand_id">Marca</label>
              <select name="brand_id" id="brand_id" class="form-control @error('brand_id') is-invalid @enderror" required>
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
            <div class="form-group mb-3">
              <label for="model_car_id">Modelo</label>
              <select name="model_car_id" id="model_car_id" class="form-control @error('model_car_id') is-invalid @enderror" required>
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
            <div class="form-group mb-3">
              <label for="year">Año</label>
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

            {{-- Transmisión --}}
            <div class="form-group mb-3">
              <label for="transmission">Transmisión</label>
              <select
                name="transmission"
                id="transmission"
                class="form-control @error('transmission') is-invalid @enderror"
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
          </div>

          {{-- Columna Derecha --}}
          <div class="col-md-6">
            {{-- Pasajeros --}}
            <div class="form-group mb-3">
              <label for="passenger_capacity">Pasajeros</label>
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

            {{-- Maletas --}}
            <div class="form-group mb-3">
              <label for="luggage_capacity">Maletas</label>
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

            {{-- Sucursal --}}
            <div class="form-group mb-3">
              <label for="branch_office_id">Sucursal</label>
              <select name="branch_office_id" id="branch_office_id"
                      class="form-control @error('branch_office_id') is-invalid @enderror" required>
                <option value="" disabled>Seleccione una sucursal</option>
                @foreach($branches as $id => $name)
                  <option value="{{ $id }}" {{ old('branch_office_id', $rentalCar->branch_office_id) == $id ? 'selected' : '' }}>
                    {{ $name }}
                  </option>
                @endforeach
              </select>
              @error('branch_office_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Precio --}}
            <div class="form-group mb-3">
              <label for="price_per_day">Precio (por día)</label>
              <input
                type="number"
                name="price_per_day"
                id="price_per_day"
                class="form-control @error('price_per_day') is-invalid @enderror"
                min="0"
                step="0.01"
                value="{{ old('price_per_day', $rentalCar->price_per_day) }}"
                required
              >
              @error('price_per_day')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        {{-- Estado --}}
<div class="form-group mb-4">
  <label>Estado</label>
  <div>
    @php
      $accidenteEnCurso = $rentalCar->accidents->contains(fn($a) => $a->status === 'in progress');
    @endphp
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="is_active" id="is_active_1" value="1"
        {{ old('is_active', $rentalCar->is_active) == '1' ? 'checked' : '' }}
        @if($accidenteEnCurso) disabled @endif>
      <label class="form-check-label" for="is_active_1">Activo</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="is_active" id="is_active_0" value="0"
        {{ old('is_active', $rentalCar->is_active) == '0' ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active_0">Inactivo</label>
    </div>
    @if($accidenteEnCurso)
      <div class="small text-danger mt-2">
        No puedes activar este auto mientras tenga un accidente en curso.
      </div>
    @endif
  </div>
</div>

        {{-- Imágenes existentes --}}
        @if($rentalCar->images->isNotEmpty())
        <div class="mb-4">
          <label class="form-label">Imágenes actuales (marca para eliminar)</label>
          <div class="d-flex flex-wrap gap-2">
            @foreach($rentalCar->images as $img)
              <div class="text-center">
                <img src="{{ tenant_asset($img->path) }}" alt="Imagen" class="img-thumbnail mb-1"
                  onerror="this.onerror=null;this.src='https://via.placeholder.com/100x70?text=No+Imagen';">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $img->id }}" id="del-img-{{ $img->id }}">
                  <label class="form-check-label small" for="del-img-{{ $img->id }}">Eliminar</label>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Nuevas imágenes --}}
        <div class="form-group mb-4">
          <label for="images">Subir nuevas imágenes</label>
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

          <div id="image-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
        </div>

        <div class="d-flex justify-content-end">
          <a href="{{ route('rental-cars.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    Array.from(e.target.files).forEach(file => {
      const reader = new FileReader();
      reader.onload = ev => {
        const img = document.createElement('img');
        img.src = ev.target.result;
        img.classList.add('img-thumbnail');
        preview.appendChild(img);
      };
      reader.readAsDataURL(file);
    });
  });
</script>
@endpush
