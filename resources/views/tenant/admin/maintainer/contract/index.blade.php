@extends('tenant.layouts.admin')

@section('title', 'Listado de Contratos')
@section('page_title', 'Contratos')

@section('content')
<div class="card">
    <div class="card-body">
        @if(!$allContractsCreated)
        <a href="{{ route('contratos.create') }}" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Nuevo Contrato
        </a>
        @endif

        @if($contracts->count())
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Contrato</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contracts as $contract)
                    <tr>
                        <td>{{ $contract['id_contract'] }}</td>
                        <td>
                            @if($contract['id_rent'])
                                Renta
                            @elseif($contract['id_parking_annual'])
                                Estacionamiento Anual
                            @elseif($contract['id_parking_daily'])
                                Estacionamiento Diario
                            @else
                                Sin tipo asignado
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('contratos.edit', $contract['id_contract']) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info">No hay contratos registrados.</div>
        @endif
    </div>
</div>
@endsection
