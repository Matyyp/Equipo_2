@extends('tenant.layouts.admin')

@section('title', 'Información de Contacto')
@section('page_title', 'Listado de Información de Contacto')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-address-book mr-2"></i>Contactos Registrados</div>
      <a href="{{ route('informacion_contacto.create', $branch->id_branch) }}"
         style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Nuevo
      </a>
    </div>

    <div class="card-body">
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
          <table id="contact-table" class="table table-striped table-bordered w-100">
            <thead class="thead-light">
              <tr>
                <th>Tipo de Contacto</th>
                <th>Dato</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data as $item)
                <tr>
                  <td>{{ $labels[$item->type_contact] ?? ucfirst($item->type_contact) }}</td>
                  <td>{{ $item->data_contact }}</td>
                  <td class="text-center">
                    <a href="{{ route('informacion_contacto.edit', $item->id_contact_information) }}"
                       class="btn btn-outline-warning btn-sm text-dark" title="Editar">
                      <i class="fas fa-pen"></i>
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="alert alert-info">No hay contactos registrados.</div>
      @endif
    </div>

    <div class="card-footer d-flex justify-content-end">
      <a href="{{ route('sucursales.show', $branch->id_branch) }}"
         style="background-color: transparent; border: 1px solid #6c757d; color: #6c757d; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
        <i class="fas fa-arrow-left mr-1"></i> Volver a Sucursales
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {
    $('#contact-table').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      },
      responsive: true,
      autoWidth: false
    });

    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar este contacto?',
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
  });
</script>
@endpush
