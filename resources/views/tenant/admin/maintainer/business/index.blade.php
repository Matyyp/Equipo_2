@extends('tenant.layouts.admin')

@section('title', 'Información del Negocio')
@section('page_title', 'Listado de Negocios')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Solo mostrar el botón si NO hay empresas --}}
        @if ($data->count() === 0)
            <a href="{{ route('empresa.create') }}" class="btn btn-success mb-3">
                <i class="fas fa-plus"></i> Ingresar Negocio
            </a>
        @else
            <div class="alert alert-info mb-3">
                Ya hay una empresa registrada. No se pueden agregar más.
            </div>
        @endif

        @if($data->count())
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre Empresa</th>
                            <th>Datos de Transferencia</th>
                            <th>Logo</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $business)
                            <tr>
                                <td>{{ $business->name_business }}</td>
                                <td>{{ $business->electronic_transfer }}</td>
                                <td>
                                    @if ($business->logo)
                                    <img src="/storage/tenants/{{ request()->getHost() }}/imagenes/{{ $business->logo }}"
                                    alt="Logo del Negocio" class="img-thumbnail" width="100">


                                    @else
                                        <span class="text-muted">No hay logo disponible.</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('empresa.edit', $business->id_business) }}"
                                       class="btn btn-warning btn-sm mb-1">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">No hay negocios registrados.</div>
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
                title: '¿Estás seguro?',
                text: "Esta acción eliminará el negocio registrado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 2500,
            showConfirmButton: false
        });
    @endif
</script>
@endpush
