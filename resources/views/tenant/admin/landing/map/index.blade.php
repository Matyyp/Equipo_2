@extends('tenant.layouts.admin')

@section('title', 'Gestión de Mapa + Contacto')
@section('page_title', 'Gestión de Mapa + Contacto')

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-map-marked-alt mr-2"></i>Mapa y Contacto</div>
      <a href="{{ route('landing.map.create') }}"
         style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Nuevo
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="map-table" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Título</th>
              <th>Ubicación</th>
              <th>Contactos</th>
              <th>Horario</th>
              <th>Botón</th>
              <th>Colores</th>
              <th>Estado Mapa</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($maps as $map)
            <tr>
              <!-- Título -->
              <td>
                @if($map->titulo_active)
                  <span class="badge bg-success">Activo</span>
                @else
                  <span class="badge bg-secondary">Inactivo</span>
                @endif
                <div class="mt-2">{{ $map->titulo }}</div>
              </td>
              
              <!-- Ubicación -->
              <td>
                <div class="d-flex flex-column small">
                  <span><strong>Ciudad:</strong> 
                    @if($map->ciudad_active)
                      <span class="badge bg-success">Activo</span>
                    @else
                      <span class="badge bg-secondary">Inactivo</span>
                    @endif
                    {{ $map->ciudad }}
                  </span>
                  <span class="mt-1"><strong>Dirección:</strong> 
                    @if($map->direccion_active)
                      <span class="badge bg-success">Activo</span>
                    @else
                      <span class="badge bg-secondary">Inactivo</span>
                    @endif
                    {{ Str::limit($map->direccion, 30) }}
                  </span>
                </div>
              </td>
              
              <!-- Contactos -->
              <td>
                @if($map->contactos_active)
                  <span class="badge bg-success">Activo</span>
                @else
                  <span class="badge bg-secondary">Inactivo</span>
                @endif
                <div class="mt-2 small">
                  @foreach(explode(',', $map->contactos) as $contacto)
                    <span class="d-block">{{ $contacto }}</span>
                  @endforeach
                </div>
              </td>
              
              <!-- Horario -->
              <td>
                @if($map->horario_active)
                  <span class="badge bg-success">Activo</span>
                @else
                  <span class="badge bg-secondary">Inactivo</span>
                @endif
                <div class="mt-2 small">{{ Str::limit($map->horario, 30) }}</div>
              </td>
              
              <!-- Botón -->
              <td>
                @if($map->boton_active)
                  <span class="badge bg-success">Activo</span>
                @else
                  <span class="badge bg-secondary">Inactivo</span>
                @endif
                <div class="mt-2 small">
                  <span class="d-block"><strong>Texto:</strong> {{ $map->texto_boton }}</span>
                  <span class="d-block"><strong>Enlace:</strong> {{ Str::limit($map->url_boton, 20) }}</span>
                </div>
              </td>
              
              <!-- Colores -->
              <td>
                <div class="d-flex flex-column gap-1 small">
                  <div class="d-flex align-items-center gap-2">
                    <span style="display:inline-block;width:15px;height:15px;background-color:{{ $map->boton_color_texto }};border-radius:50%;border:1px solid #ccc;"></span>
                    Texto Botón
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <span style="display:inline-block;width:15px;height:15px;background-color:{{ $map->boton_color }};border-radius:50%;border:1px solid #ccc;"></span>
                    Fondo Botón
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <span style="display:inline-block;width:15px;height:15px;background-color:{{ $map->color_tarjeta }};border-radius:50%;border:1px solid #ccc;"></span>
                    Fondo Tarjeta
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <span style="display:inline-block;width:15px;height:15px;background-color:{{ $map->color_texto_tarjeta }};border-radius:50%;border:1px solid #ccc;"></span>
                    Texto Tarjeta
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <span style="display:inline-block;width:15px;height:15px;background-color:{{ $map->color_mapa }};border-radius:50%;border:1px solid #ccc;"></span>
                    Color Mapa
                  </div>
                </div>
              </td>
              
              <!-- Estado -->
              <td>
                @if($map->map_active)
                  <span class="badge bg-success">Activo</span>
                @else
                  <span class="badge bg-secondary">Inactivo</span>
                @endif
              </td>
              
              <!-- Acciones -->
              <td>
                <div class="d-flex gap-1">
                  <a href="{{ route('landing.map.edit', $map->id_map) }}" class="btn btn-outline-secondary btn-sm" title="Editar">
                    <i class="fas fa-pen"></i>
                  </a>
                  <form action="{{ route('landing.map.destroy', $map->id_map) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .badge {
    font-size: 0.75em;
    padding: 0.35em 0.65em;
  }
  #map-table td {
    vertical-align: middle;
  }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  $('#map-table').DataTable({
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
    responsive: true,
    searching: true,
    paging: true,
    info: true,
    ordering: true,
    columnDefs: [
      { orderable: false, targets: [5, 7] } // Deshabilitar ordenación para colores y acciones
    ]
  });

  // Confirmación para eliminar
  $('.delete-form').on('submit', function(e) {
    e.preventDefault();
    Swal.fire({
      title: '¿Estás seguro?',
      text: "¡No podrás revertir esto!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, eliminarlo!'
    }).then((result) => {
      if (result.isConfirmed) {
        this.submit();
      }
    });
  });
});
</script>
@endpush