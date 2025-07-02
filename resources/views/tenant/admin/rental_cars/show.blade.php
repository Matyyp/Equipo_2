{{-- resources/views/tenant/admin/rental_cars/show.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Ver vehículo de Arriendo')
@section('page_title', 'Ver vehículo de Arriendo')

@section('content')
<div class="container-fluid">
  {{-- Encabezado con icono, título y acciones --}}
  <div class="card mb-4">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <div class="d-flex align-items-center">
        <i class="fas fa-car mr-2 fa-lg"></i>
        <h5 class="mb-0">Detalle del vehículo</h5>
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
          @if($rentalCar->visual_status === 'activo')
            <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
          @else
            <span class="border border-secondary text-secondary px-2 py-1 rounded">Inactivo</span>
          @endif
        </dd>

        <dt class="col-sm-3">Sucursal</dt>
        <dd class="col-sm-9">
          @if($rentalCar->branchOffice)
            {{ $rentalCar->branchOffice->name_branch_offices }}
          @else
            <span class="text-muted">—</span>
          @endif
        </dd>
        <dt class="col-sm-3">Pasajeros</dt>
        <dd class="col-sm-9">{{ $rentalCar->passenger_capacity }}</dd>

        <dt class="col-sm-3">Transmisión</dt>
        <dd class="col-sm-9">{{ ucfirst($rentalCar->transmission) }}</dd>

        <dt class="col-sm-3">Maletas</dt>
        <dd class="col-sm-9">{{ $rentalCar->luggage_capacity }}</dd>

        <dt class="col-sm-3">Precio/Día</dt>
        <dd class="col-sm-9">
          ${{ number_format($rentalCar->price_per_day, 0, ',', '.') }}
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
            <div class="border rounded overflow-hidden mr-2" style="width:200px; height:130px;">
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
      No hay imágenes disponibles para este vehículo.
    </div>
  @endif
  <div class="d-flex justify-content-end">
    <a href="{{ route('rental-cars.index') }}" class="btn btn-secondary me-2">Volver</a>
  </div>
@endsection
