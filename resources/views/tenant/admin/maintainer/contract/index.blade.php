@extends('tenant.layouts.admin')

@section('title', 'Listado de Contratos')
@section('page_title', 'Contratos por Sucursal')

@section('content')
<div class="card">
  <div class="card-body">

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Tipo de Contrato</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          {{-- Contrato de Renta --}}
          <tr>
            <td>Arriendo</td>
            <td>
              @if($contracts->firstWhere('id_rent'))
                <span class="badge bg-success">Activo</span>
              @else
                <span class="badge bg-secondary">Inactivo</span>
              @endif
            </td>
            <td>
              @if($contracts->firstWhere('id_rent'))
                <a href="{{ route('contratos.edit', $contracts->firstWhere('id_rent')['id_contract']) }}" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i> Editar
                </a>
              @else
                <a href="{{ route('contratos.create', ['branch' => $branchId, 'type' => 'rent']) }}" class="btn btn-success btn-sm">
                  <i class="fas fa-plus"></i> Activar
                </a>
              @endif
            </td>
          </tr>

          {{-- Contrato Estacionamiento Diario --}}
          <tr>
            <td>Estacionamiento Diario</td>
            <td>
              @if($contracts->firstWhere('id_parking_daily'))
                <span class="badge bg-success">Activo</span>
              @else
                <span class="badge bg-secondary">Inactivo</span>
              @endif
            </td>
            <td>
              @if($contracts->firstWhere('id_parking_daily'))
                <a href="{{ route('contratos.edit', $contracts->firstWhere('id_parking_daily')['id_contract']) }}" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i> Editar
                </a>
              @else
                <a href="{{ route('contratos.create', ['branch' => $branchId, 'type' => 'parking_daily']) }}" class="btn btn-success btn-sm">
                  <i class="fas fa-plus"></i> Activar
                </a>
              @endif
            </td>
          </tr>

          {{-- Contrato Estacionamiento Mensual --}}
          <tr>
            <td>Estacionamiento Mensual</td>
            <td>
              @if($contracts->firstWhere('id_parking_annual'))
                <span class="badge bg-success">Activo</span>
              @else
                <span class="badge bg-secondary">Inactivo</span>
              @endif
            </td>
            <td>
              @if($contracts->firstWhere('id_parking_annual'))
                <a href="{{ route('contratos.edit', $contracts->firstWhere('id_parking_annual')['id_contract']) }}" class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i> Editar
                </a>
              @else
                <a href="{{ route('contratos.create', ['branch' => $branchId, 'type' => 'parking_annual']) }}" class="btn btn-success btn-sm">
                  <i class="fas fa-plus"></i> Activar
                </a>
              @endif
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
