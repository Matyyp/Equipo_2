{{-- resources/views/tenant/admin/rental_cars/show.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Ver Auto de Arriendo')
@section('page_title', 'Ver Auto de Arriendo')

@section('content')
<div class="container-fluid">
  {{-- Encabezado con icono, título y acciones --}}
  <div class="card mb-4">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <div class="d-flex align-items-center">
        <i class="fas fa-car mr-2 fa-lg"></i>
        <h5 class="mb-0">Detalle Auto</h5>
      </div>
      <div class="ml-auto">
        <a href="{{ route('rental-cars.edit', $rentalCar) }}"
           class="btn btn-outline-light btn-sm me-2"
           title="Editar">
          <i class="fas fa-edit"></i>
        </a>
        <a href="{{ route('rental-cars.index') }}"
           class="btn btn-outline-light btn-sm"
           title="Volver">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
    </div>

    <div class="card-body">
      {{-- Detalles en formato descripción --}}
      <dl class="row mb-0">
        <dt class="col-sm-3">Marca</dt>
        <dd class="col-sm-9">{{ $rentalCar->brand->name_brand }}</dd>

        <dt class="col-sm-3">Modelo</dt>
        <dd class="col-sm-9">{{ $rentalCar->model->name_model }}</dd>

        <dt class="col-sm-3">Año</dt>
        <dd class="col-sm-9">{{ $rentalCar->year }}</dd>

        <dt class="col-sm-3">Estado</dt>
        <dd class="col-sm-9">
          @if($rentalCar->is_active)
            <span class="badge bg-success">Activo</span>
          @else
            <span class="badge bg-secondary">Inactivo</span>
          @endif
        </dd>
      </dl>
    </div>
  </div>

  {{-- Sección de imágenes --}}
  @if($rentalCar->images->isNotEmpty())
    <div class="card shadow-sm">
      <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Imágenes</h5>
      </div>
      <div class="card-body">
        <div class="d-flex flex-wrap gap-3">
          @foreach($rentalCar->images as $img)
            <div class="border rounded overflow-hidden" style="width:200px; height:130px;">
              <img
                src="{{ tenant_asset($img->path) }}"
                alt="Imagen {{ $img->id }}"
                class="w-100 h-100 object-cover"
                onerror="this.onerror=null;this.src='https://via.placeholder.com/200x130?text=No+Imagen';"
              >
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @else
    <div class="alert alert-info">
      No hay imágenes disponibles para este auto.
    </div>
  @endif
</div>
@endsection
