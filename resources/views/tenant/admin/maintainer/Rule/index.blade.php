@extends('tenant.layouts.admin')

@section('title', 'Reglas de contratos')
@section('page_title', 'Listado de Reglas')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Reglas Registradas</h3>
        <a href="{{ route('reglas.create') }}" class="btn btn-success btn-sm float-right">
            <i class="fas fa-plus"></i> Nueva Regla
        </a>
    </div>

    <div class="card-body p-0">
        @if ($Rule->count())
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Tipo de Contrato</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($Rule as $rule)
                            <tr>
                                <td>{{ $rule->name }}</td>
                                <td>{{ $rule->description }}</td>
                                <td>
                                    @switch($rule->type_contract)
                                        @case('rent')
                                            <span class="badge bg-primary">Renta</span>
                                            @break
                                        @case('parking_daily')
                                            <span class="badge bg-info text-dark">Estacionamiento Diario</span>
                                            @break
                                        @case('parking_annual')
                                            <span class="badge bg-secondary">Estacionamiento Anual</span>
                                            @break
                                        @default
                                            <span class="badge bg-light text-muted">Sin tipo</span>
                                    @endswitch
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('reglas.edit', $rule->id_rule) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info m-3">No hay reglas registradas.</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar esta regla?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
