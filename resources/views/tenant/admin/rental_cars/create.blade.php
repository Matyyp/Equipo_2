@extends('tenant.layouts.admin')

@section('title', 'Crear Auto de Arriendo')
@section('page_title', 'Crear Auto de Arriendo')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0">Nuevo Auto de Arriendo</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('rental-cars.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Marca --}}
        <div class="mb-3">
          <label for="brand_id" class="form-label">Marca</label>
          <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
            <option value="" disabled selected>Seleccione una marca</option>
            @foreach($brands as $id => $name)
              <option value="{{ $id }}" {{ old('brand_id') == $id ? 'selected' : '' }}>
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
            <option value="" disabled selected>Seleccione un modelo</option>
            @foreach($models as $id => $name)
              <option value="{{ $id }}" {{ old('model_car_id') == $id ? 'selected' : '' }}>
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
            value="{{ old('year') }}"
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
              <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
              Activo
            </label>
            <label>
              <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
              Inactivo
            </label>
          </div>
        </div>

        {{-- Sucursal --}}
        <div class="mb-3">
          <label for="branch_office_id" class="form-label">Sucursal</label>
          <select name="branch_office_id" id="branch_office_id"
                  class="form-select @error('branch_office_id') is-invalid @enderror" required>
            <option value="" disabled selected>Seleccione una sucursal</option>
            @foreach($branches as $id => $name)
              <option value="{{ $id }}" {{ old('branch_office_id') == $id ? 'selected' : '' }}>
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
          <input
            type="number"
            name="price_per_day"
            id="price_per_day"
            class="form-control @error('price_per_day') is-invalid @enderror"
            min="0"
            step="0.01"
            value="{{ old('price_per_day', $rentalCar->price_per_day ?? '') }}"
            required
          >
          @error('price_per_day')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Imágenes --}}
        <div class="mb-3">
          <label for="images" class="form-label">Imágenes</label>
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

          {{-- Contenedor para previsualización --}}
          <div id="image-preview" class="mt-3 d-flex flex-wrap"></div>
        </div>

        <div class="d-flex justify-content-end">
          <a href="{{ route('rental-cars.index') }}" class="btn btn-secondary me-1">Cancelar</a>
          <button type="submit" class="btn btn-primary ml-1">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.getElementById('images').addEventListener('change', function(event) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = ''; // limpiar previas
    Array.from(event.target.files).forEach(file => {
      const reader = new FileReader();
      reader.onload = e => {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.classList.add('img-thumbnail', 'me-2', 'mb-2');
        img.style.width = '100px';
        preview.appendChild(img);
      };
      reader.readAsDataURL(file);
    });
  });
</script>
@endpush
