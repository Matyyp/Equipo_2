@extends('tenant.layouts.admin')

@section('title', 'Sucursal: ' . $sucursal->name_branch_offices)
@section('page_title', 'Detalle de la Sucursal')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div>
        <i class="fas fa-store-alt me-2"></i> {{ $sucursal->name_branch_offices }}
      </div>
      <a href="{{ route('sucursales.index') }}"
         style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-arrow-left"></i> Volver al listado
      </a>
    </div>

    <div class="card-body">
      <p><strong>Horario:</strong> {{ $sucursal->schedule }}</p>
      <p><strong>Calle:</strong> {{ $sucursal->street }}</p>
      <p><strong>Región:</strong> {{ $sucursal->branch_office_location->location_region->name_region ?? 'No asignada' }}</p>
      <p><strong>Comuna:</strong> {{ $sucursal->branch_office_location->commune ?? 'No asignada' }}</p>
      <hr>

    <div class="d-flex flex-wrap justify-content-center mt-4">
      <a href="{{ route('sucursales.edit', $sucursal->id_branch) }}" class="btn btn-outline-secondary text-dark btn-lg rounded mx-2 mb-2">
        <i class="fas fa-pen"></i> Editar
      </a>

      <form action="{{ route('sucursales.destroy', $sucursal->id_branch) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas desactivar esta sucursal?');" class="mx-2 mb-2">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-secondary text-dark btn-lg rounded">
              <i class="fas fa-ban"></i> Desactivar
          </button>
      </form>

      <a href="{{ route('servicios.show', $sucursal->id_branch) }}" class="btn btn-outline-secondary text-dark btn-lg rounded mx-2 mb-2">
        <i class="fas fa-concierge-bell"></i> Servicios
      </a>
      <a href="{{ route('contratos.show', $sucursal->id_branch) }}" class="btn btn-outline-secondary text-dark btn-lg rounded mx-2 mb-2">
        <i class="fas fa-file-contract"></i> Contratos
      </a>
      <a href="{{ url('informacion_contacto/' . $sucursal->id_branch) }}" class="btn btn-outline-secondary text-dark btn-lg rounded mx-2 mb-2">
        <i class="fas fa-address-book"></i> Información de Contacto
      </a>
      <a href="{{ url('trabajadores/' . $sucursal->id_branch) }}" class="btn btn-outline-secondary text-dark btn-lg rounded mx-2 mb-2">
        <i class="fas fa-users"></i> Trabajadores
      </a>
      <a href="{{ route('lavados.show', $sucursal->id_branch) }}" class="btn btn-info btn-lg rounded mx-2 mb-2">
        <i class="fas fa-soap"></i> Lavado de auto
      </a>

    </div>



    </div>
  </div>
</div>
@endsection
