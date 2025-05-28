@php
    use App\Models\Map;
    
    // Obtener todos los mapas activos ordenados
    $maps = Map::where('map_active', true)->orderBy('created_at', 'desc')->get();
@endphp

<!-- Mapa + Contacto -->
<section id='contacto' class="py-16 bg-gray-100">
  <div class="container mx-auto px-4">
    @foreach($maps as $index => $map)
      @php
        // Validar y procesar coordenadas
        $coordenadas = explode(',', $map->coordenadas_mapa);
        $latitud = count($coordenadas) > 0 ? trim($coordenadas[0]) : 0;
        $longitud = count($coordenadas) > 1 ? trim($coordenadas[1]) : 0;
        
        // Determinar la dirección basado en el índice (par o impar)
        $mapDirection = $index % 2 === 0 ? 'md:grid-cols-2' : 'md:grid-cols-2';
        $mapOrder = $index % 2 === 0 ? '' : 'md:order-last';
      @endphp
      
      <div class="grid grid-cols-1 {{ $mapDirection }} gap-12 items-center text-center md:text-left mb-16">
        <!-- Mapa -->
        <div data-aos="fade-right" class="{{ $mapOrder }}">
          <div id="map-{{ $map->id_map }}" class="w-full h-[300px] md:h-[400px] rounded-xl shadow-lg border-4" 
               style="border-color: {{ $map->color_mapa }}"></div>
        </div>
        
        <!-- Tarjeta de Contacto -->
        <div data-aos="fade-left">
          <div class="rounded-2xl shadow-lg p-6 space-y-4" 
               style="background-color: {{ $map->color_tarjeta }}; color: {{ $map->color_texto_tarjeta }}">
            
            @if($map->titulo_active)
              <p class="uppercase text-sm opacity-80">Sucursal</p>
              <h2 class="text-2xl font-bold">{{ $map->titulo }}</h2>
            @endif
            
            @if($map->direccion_active || $map->ciudad_active)
              <p class="text-sm opacity-80">
                @if($map->direccion_active) {{ $map->direccion }} @endif
                @if($map->direccion_active && $map->ciudad_active) — @endif
                @if($map->ciudad_active) {{ $map->ciudad }} @endif
              </p>
            @endif
            
            @if($map->contactos_active && $map->contactos)
              <div class="border-t pt-4 space-y-2" style="border-color: {{ $map->color_texto_tarjeta }}; opacity: 0.2">
                <p class="font-semibold text-sm" style="color: {{ $map->boton_color }}">CONTACTOS</p>
                @foreach(explode(',', $map->contactos) as $contacto)
                  <p>{{ trim($contacto) }}</p>
                @endforeach
              </div>
            @endif
            
            @if($map->horario_active && $map->horario)
              <div class="flex items-start gap-2 text-sm p-3 rounded-lg" style="background-color: {{ $map->color_mapa }}; opacity: 0.8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" strokeLinejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 4h10M5 11h14M5 15h14M5 19h14" />
                </svg>
                <p>{{ $map->horario }}</p>
              </div>
            @endif
            
            @if($map->boton_active && $map->texto_boton)
              <a href="{{ $map->url_boton ?? '#' }}" 
                 class="inline-block px-6 py-2 rounded-xl font-semibold shadow-md transition-colors"
                 style="background-color: {{ $map->boton_color }}; color: {{ $map->boton_color_texto }}">
                {{ $map->texto_boton }} →
              </a>
            @endif
          </div>
        </div>
      </div>

      @push('scripts')
      <script>
        // Inicializar mapa para esta ubicación
        var map{{ $map->id_map }} = L.map('map-{{ $map->id_map }}').setView(
          [{{ $latitud }}, {{ $longitud }}], 
          15
        );
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
          maxZoom: 19 
        }).addTo(map{{ $map->id_map }});
        
        L.marker([{{ $latitud }}, {{ $longitud }}])
          .addTo(map{{ $map->id_map }})
          .bindPopup('{{ $map->titulo }}')
          .openPopup();
      </script>
      @endpush
    @endforeach
  </div>
</section>