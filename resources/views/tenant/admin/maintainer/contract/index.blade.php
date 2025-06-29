@extends('tenant.layouts.admin')

@section('title', 'Listado de Contratos')
@section('page_title', 'Contratos por Sucursal')
@push('styles')
<style>
  .btn-outline-info.text-info:hover,
.btn-outline-info.text-info:focus {
  color: #fff !important;
}
</style>
@endpush
@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-file-contract mr-2"></i>Contratos disponibles para la sucursal
    </div>

    <div class="card-body">
      {{-- Alerta si faltan datos --}}
@if(!$hasContactInfo || !$hasRules)
<div class="alert d-flex flex-column gap-2 mb-4 rounded" style="background-color: #17a2b8; color: white;">
  <div class="d-flex align-items-center gap-2">
    <i class="fas fa-exclamation-circle mr-2"></i>
    <strong>Antes de activar contratos debes completar:</strong>
  </div>
  <ul class="mb-0 ps-4">
    @unless($hasContactInfo)
    <li class="d-flex align-items-center gap-2 flex-wrap py-1">
      <span class="mr-2">Información de contacto </span>
      <a href="{{ url('/informacion_contacto/' . $branchId) }}" 
         class="btn btn-sm btn-outline-light" style="white-space: nowrap;">
        <i class="fas fa-address-card mr-1"></i> Ir a Contacto
      </a>
    </li>
    @endunless
    @unless($hasRules)
    <li class="d-flex align-items-center gap-2 flex-wrap py-1">
      <span class="mr-2">Reglas de contrato </span>
      <a href="{{ route('reglas.index') }}" 
         class="btn btn-sm btn-outline-light" style="white-space: nowrap;">
        <i class="fas fa-file-contract mr-1"></i> Ir a Reglas
      </a>
    </li>
    @endunless
  </ul>
</div>
@endif

      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle w-100">
          <thead class="thead-light">
            <tr>
              <th>Tipo de Contrato</th>
              <th class="text-center">Estado</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @php
              $tipos = [
                'rent' => 'Renta',
                'parking_daily' => 'Estacionamiento Diario',
                'parking_annual' => 'Estacionamiento Anual'
              ];
            @endphp

            @foreach($tipos as $key => $nombre)
              @php
                $idKey = "id_" . $key;
                $contrato = $contracts->firstWhere($idKey);
              @endphp
              <tr>
                <td>{{ $nombre }}</td>
                <td class="text-center">
                  @if($contrato)
                    <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                  @else
                    <span class="border border-secondary text-secondary px-2 py-1 rounded">Inactivo</span>
                  @endif
                </td>
                <td class="text-center">
                  @if($contrato)
                    <a href="{{ route('contratos.edit', $contrato['id_contract']) }}"
                       class="btn btn-outline-info btn-sm text-info" title="Editar">
                      <i class="fas fa-pen"></i> Editar
                    </a>
                  @else
                    <a href="{{ $hasContactInfo && $hasRules ? route('contratos.create', ['branch' => $branchId, 'type' => $key]) : '#' }}"
                       class="btn btn-sm btn-outline-success {{ (!$hasContactInfo || !$hasRules) ? 'disabled' : '' }}"
                       title="{{ (!$hasContactInfo || !$hasRules) ? 'Debe completar contacto y reglas para activar' : '' }}">
                      <i class="fas fa-plus"></i> Activar
                    </a>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Botón de volver -->
    <div class="card-footer d-flex justify-content-end">
      <a href="{{ route('sucursales.show', $branchId) }}"
         style="background-color: transparent; border: 1px solid #6c757d; color: #6c757d; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
        <i class="fas fa-arrow-left mr-1"></i> Volver a Sucursales
      </a>
    </div>
  </div>
</div>
@endsection
