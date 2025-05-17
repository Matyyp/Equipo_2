@extends('tenant.layouts.admin')

@section('title', 'Ver Auto de Arriendo')
@section('page_title', 'Ver Auto de Arriendo')

@section('content')
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Detalle Auto #{{ $rentalCar->id }}</h2>
    <div>
      <a href="{{ route('rental-cars.edit', $rentalCar) }}" class="btn btn-warning">Editar</a>
      <a href="{{ route('rental-cars.index') }}" class="btn btn-secondary">Volver</a>
    </div>
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <p><strong>Marca:</strong> {{ $rentalCar->brand->name_brand }}</p>
      <p><strong>Modelo:</strong> {{ $rentalCar->model->name_model }}</p>
      <p><strong>Año:</strong> {{ $rentalCar->year }}</p>
      <p><strong>Estado:</strong>
        @if($rentalCar->is_active)
          <span class="badge bg-success">Activo</span>
        @else
          <span class="badge bg-secondary">Inactivo</span>
        @endif
      </p>
    </div>
  </div>

  @if($rentalCar->images->count())
    <div class="card shadow-sm">
      <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Imágenes</h5>
      </div>
      <div class="card-body">
        <div class="d-flex flex-wrap gap-3">
            @foreach($rentalCar->images as $img)
                <div style="width:200px; height:130px; overflow:hidden; border:1px solid #ccc;">
                    <img 
                    src="{{ asset('storage/' . $img->path) }}" 
                    alt="Imagen {{ $img->id }}" 
                    class="img-fluid"
                    onerror="this.onerror=null;this.src='https://via.placeholder.com/200x130?text=No+Imagen';"
                    >
                </div>
            @endforeach
        </div>
      </div>
    </div>
  @endif
</div>
@endsection
