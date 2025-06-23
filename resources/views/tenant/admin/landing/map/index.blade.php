@extends('tenant.layouts.admin')

@section('title', 'Vista Previa Mapa + Contacto')
@section('page_title', 'Vista Previa Mapa + Contacto')

@push('styles')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
  <style>
    .leaflet-container {
      height: 100%;
      width: 100%;
    }

  </style>
  <style>
  .map-border {
    border-width: 4px !important;
    border-style: solid;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card">


    <div class="card-header bg-secondary text-white">
      <div class="row w-100 align-items-center">
        <div class="col">
          <i class="fas fa-map-marked-alt mr-2"></i>
          <span>Previsualización Mapa + Contacto</span>
        </div>
        <div class="col-auto ms-auto">
          <a href="{{ route('landing.map.create') }}"
            style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
            <i class="fas fa-plus"></i> Nuevo
          </a>
        </div>
      </div>
    </div>

    <div class="card-body">
      @php
          use App\Models\Map;
          $maps = Map::orderByDesc('created_at')->get();
      @endphp

      @if($maps->isEmpty())
        <div class="alert alert-info">No hay registros de mapas disponibles.</div>
      @else
        @foreach($maps as $index => $map)
          @php
              $coords = explode(',', $map->coordenadas_mapa ?? '');
              $lat = trim($coords[0] ?? '0');
              $lng = trim($coords[1] ?? '0');
              $reverse = $index % 2 !== 0;
          @endphp

          <div class="admin-map-wrapper">
            <div class="row align-items-center">
              <div class="col-md-6 {{ $reverse ? 'order-md-2' : '' }}">
<div id="map-{{ $map->id_map }}" class="map-border" style="border-color: {{ $map->color_mapa }}; height: 300px;"></div>
                
              </div>

              <div class="col-md-6">
                <div class="rounded shadow-lg p-4 mt-4 mt-md-0" style="background-color: {{ $map->color_tarjeta }}; color: {{ $map->color_texto_tarjeta }}">
                  @if($map->titulo_active)
                    <p class="text-uppercase small opacity-75">{{ $map->titulo }}</p>
                  @endif

                  @if($map->ciudad_active)
                    <h4 class="fw-bold">{{ $map->ciudad }}</h4>
                  @endif

                  @if($map->direccion_active)
                    <p class="mb-2">{{ $map->direccion }}</p>
                  @endif

                  @if($map->contactos_active && $map->contactos)
                    <div class="border-top pt-2" style="border-color: {{ $map->color_texto_tarjeta }}">
                      <p class="mb-1 fw-semibold small" style="color: {{ $map->boton_color }}">CONTACTOS</p>
                      @foreach(explode(',', $map->contactos) as $contacto)
                        <p class="mb-0">{{ trim($contacto) }}</p>
                      @endforeach
                    </div>
                  @endif

                  @if($map->horario_active && $map->horario)
                    <div class="mt-2 d-flex align-items-start gap-2 text-sm p-2 rounded">
                      <i class="fas fa-clock mt-1"></i>
                      <p class="mb-0">{{ $map->horario }}</p>
                    </div>
                  @endif

                  @if($map->boton_active && $map->texto_boton)
                    <a href="{{ $map->url_boton ?? '#' }}"
                       class="btn btn-sm mt-3"
                       style="background-color: {{ $map->boton_color }}; color: {{ $map->boton_color_texto }}">
                      {{ $map->texto_boton }} →
                    </a>
                  @endif
                </div>

                <!-- Acciones -->
                <div class="mt-3 d-flex gap-2">
                  <a href="{{ route('landing.map.edit', $map->id_map) }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-pen"></i> 
                  </a>
                  <form action="{{ route('landing.map.destroy', $map->id_map) }}"
                        method="POST"
                        class="delete-form d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-info btn-sm">
                      <i class="fas fa-trash"></i> 
                    </button>
                  </form>
                </div>

              </div>
            </div>
          </div>

          @push('scripts')
          <script>
            document.addEventListener('DOMContentLoaded', function () {
              var map = L.map('map-{{ $map->id_map }}').setView([{{ $lat }}, {{ $lng }}], 15);
              L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
              }).addTo(map);
              L.marker([{{ $lat }}, {{ $lng }}]).addTo(map)
                .bindPopup('{{ $map->titulo ?? 'Ubicación' }}')
                .openPopup();
            });
          </script>
          @endpush
        @endforeach
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
          e.preventDefault();
          Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              this.submit();
            }
          });
        });
      });
    });
  </script>
@endpush
