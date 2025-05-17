@extends('tenant.layouts.admin')

@section('title', 'Listado de Autos de Arriendo')
@section('page_title', 'Autos de Arriendo')

@section('content')
<div class="container mt-4">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Autos de Arriendo</h2>
    <a href="{{ route('rental-cars.create') }}" class="btn btn-primary">+ Nuevo Auto</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <table class="table table-striped mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Estado</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rentalCars as $car)
            <tr>
              <td>{{ $car->id }}</td>
              <td>{{ $car->brand->name_brand }}</td>
              <td>{{ $car->model->name_model }}</td>
              <td>{{ $car->year }}</td>
              <td>
                @if($car->is_active)
                  <span class="badge bg-success">Activo</span>
                @else
                  <span class="badge bg-secondary">Inactivo</span>
                @endif
              </td>
              <td class="text-center">
                <a href="{{ route('rental-cars.show', $car) }}" class="btn btn-sm btn-info">Ver</a>
                <a href="{{ route('rental-cars.edit', $car) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('rental-cars.destroy', $car) }}" method="POST" class="d-inline-block"
                      onsubmit="return confirm('¿Seguro que quieres eliminar este auto?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4">No hay autos registrados.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
