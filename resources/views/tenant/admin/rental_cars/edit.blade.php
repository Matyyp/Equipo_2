@extends('tenant.layouts.admin')

@section('title', 'Editar Auto de Arriendo')
@section('page_title', 'Editar Auto de Arriendo')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0">Editar Auto de Arriendo #{{ $rentalCar->id }}</h5>
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

        {{-- Imágenes existentes --}}
        @if($rentalCar->images->count())
            <div class="mb-3">
                <label class="form-label">Imágenes actuales</label>
                <div class="d-flex flex-wrap gap-2">
                @foreach($rentalCar->images as $img)
                    <div style="width:100px; height:70px; overflow:hidden; border:1px solid #ccc;">
                    <img 
                        src="{{ asset('storage/' . $img->path) }}" 
                        alt="Imagen {{ $img->id }}" 
                        class="img-fluid"
                        onerror="this.onerror=null;this.src='https://via.placeholder.com/100x70?text=No+Imagen';"
                    >
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
            multiple
          >
          @error('images.*')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex justify-content-end">
          <a href="{{ route('rental-cars.index') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
