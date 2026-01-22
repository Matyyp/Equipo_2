@extends('tenant.layouts.admin')

@section('title', 'Seleccionar Registros')
@section('page_title', 'Seleccionar Registros')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <i class="fas fa-list mr-2"></i> Seleccione los tickets a Imprimir
             <a href="{{ route('estacionamiento.index') }}"
               style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>
       

        <div class="card-body">

            <form action="{{ route('tickets.print') }}" method="POST">
                @csrf

                <div class="mb-3 font-weight-bold">
                    Seleccione uno o más registros:
                </div>

                <div class="list-group" style="max-height: 450px; overflow-y:auto;">

                    @forelse($registros as $r)

                        <label class="list-group-item d-flex justify-content-between align-items-center">

                            <div class="d-flex align-items-start">

                                {{-- Checkbox --}}
                                <input type="checkbox"
                                    name="registros[]"
                                    value="{{ $r['id'] }}"
                                    class="mr-3 mt-1"
                                >

                                <div>
                                    {{-- Nombre y patente --}}
                                    <div class="font-weight-bold">
                                        {{ $r['owner_name'] }}
                                        <span class="badge badge-info ml-2">
                                            {{ $r['patent'] }}
                                        </span>
                                    </div>

                                    {{-- Fechas --}}
                                    <small class="text-muted">
                                        <strong>Inicio:</strong> {{ $r['start_date'] }}
                                        &nbsp; | &nbsp;
                                        <strong>Término:</strong> {{ $r['end_date'] }}
                                    </small>
                                </div>

                            </div>

                        </label>

                    @empty

                        <div class="alert alert-info">
                            No hay registros disponibles.
                        </div>

                    @endforelse

                </div>

                <button class="btn btn-primary mt-3">
                    Enviar selección
                </button>

            </form>

        </div>
    </div>

</div>
@endsection
