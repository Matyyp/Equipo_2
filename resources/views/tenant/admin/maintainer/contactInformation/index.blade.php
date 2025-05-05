@extends('tenant.layouts.admin')

@section('title', 'Información de Contacto')
@section('page_title', 'Listado de Información de Contacto')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Información de Contacto</h3>
        <a href="{{ route('informacion_contacto.create', $branch->id_branch) }}" class="btn btn-dark btn-sm">
            <i class="fas fa-info-circle"></i> Agregar Contacto
        </a>
    </div>

    <div class="card-body p-0">
        @php
            $labels = [
                'phone' => 'Teléfono',
                'email' => 'Correo Electrónico',
                'whatsapp' => 'WhatsApp',
                'website' => 'Sitio Web',
                'other' => 'Otro'
            ];
        @endphp

        @if ($data->count())
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tipo de Contacto</th>
                            <th>Dato</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $labels[$item->type_contact] ?? ucfirst($item->type_contact) }}</td>
                                <td>{{ $item->data_contact }}</td>
                                <td class="text-right">
                                    <a href="{{ route('informacion_contacto.edit', $item->id_contact_information) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info m-3">No hay contactos registrados.</div>
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
                title: '¿Eliminar contacto?',
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
