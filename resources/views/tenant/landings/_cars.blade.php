{{-- resources/views/tenant/landings/_cars.blade.php --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
  @forelse($cars as $index => $car)
    <div class="relative bg-white rounded-xl overflow-hidden shadow-lg group transition-all duration-300 hover:shadow-xl hover:-translate-y-2 flex flex-col
                animate-slide-in-right opacity-0"
         style="animation-delay: {{ $index * 75 }}ms;">

      {{-- Imagen --}}
      @if($car->images->isNotEmpty())
        <div class="relative w-full h-[230px] border-4 border-gray-100 rounded-t-xl overflow-hidden">
          <div class="swiper swiper-auto h-full">
            <div class="swiper-wrapper h-full">
              @foreach($car->images as $img)
                <div class="swiper-slide">
                  <img
                    src="{{ tenant_asset($img->path) }}"
                    alt="{{ $car->brand->name_brand }} {{ $car->model->name_model }}"
                    class="w-full h-full object-cover"
                    loading="lazy"
                  >
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @else
        <div class="w-full h-[230px] flex items-center justify-center text-gray-400 border-4 border-gray-100 rounded-t-xl" style="background: linear-gradient(to bottom right, #f3f4f6, #e5e7eb);">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
      @endif

      {{-- Marca, modelo y año --}}
      <div class="border-x-4 border-b-4 border-gray-100 rounded-b-xl px-2 text-center">
        <p class="text-sm text-gray-800 font-semibold truncate leading-tight py-2">
          {{ $car->brand->name_brand }} {{ $car->model->name_model }} - {{ $car->year }}
        </p>
      </div>

      {{-- Overlay --}}
      <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/95 via-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col px-4 pb-4 pointer-events-none">
        <div class="text-white text-sm mt-auto pointer-events-auto">

          {{-- Características (2 columnas) --}}
          <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="flex items-center">
              <div class="bg-blue-600/20 p-2 rounded-lg mr-2">
                <i class="fas fa-user-friends text-blue-400"></i>
              </div>
              <div>
                <p class="text-xs text-gray-300">Pasajeros</p>
                <p class="font-medium">{{ $car->passenger_capacity }}</p>
              </div>
            </div>

            <div class="flex items-center">
              <div class="bg-blue-600/20 p-2 rounded-lg mr-2">
                <i class="fas fa-suitcase-rolling text-blue-400"></i>
              </div>
              <div>
                <p class="text-xs text-gray-300">Maletas</p>
                <p class="font-medium">{{ $car->luggage_capacity }}</p>
              </div>
            </div>

            <div class="flex items-center">
              <div class="bg-blue-600/20 p-2 rounded-lg mr-2">
                <i class="fas fa-cog text-blue-400"></i>
              </div>
              <div>
                <p class="text-xs text-gray-300">Transmisión</p>
                <p class="font-medium">{{ ucfirst($car->transmission) }}</p>
              </div>
            </div>

            <div class="flex items-center">
              <div class="bg-blue-600/20 p-2 rounded-lg mr-2">
                <i class="fas fa-dollar-sign text-blue-400"></i>
              </div>
              <div>
                <p class="text-xs text-gray-300">Precio</p>
                <p class="font-medium">
                  @if($car->price_per_day > 0)
                    ${{ number_format($car->price_per_day, 0, ',', '.') }}/día
                  @else
                    Consultar
                  @endif
                </p>
              </div>
            </div>
          </div>

          {{-- Botón --}}
          <a href="{{ route('cars.reserve', $car) }}"
            class="block w-full text-center py-2.5 px-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg transition-all duration-300 shadow-md hover:shadow-lg text-sm">
            Reservar ahora <i class="fas fa-arrow-right ml-2"></i>
          </a>
        </div>
      </div>
    </div>
  @empty
    <div class="col-span-full text-center py-16">
      <div class="max-w-md mx-auto">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">No hay autos disponibles</h3>
        <p class="mt-2 text-sm text-gray-500">Prueba con otros filtros o vuelve más tarde</p>
      </div>
    </div>
  @endforelse
</div>

@push('styles')
<style>
  @keyframes slideInRight {
    from {
      transform: translateX(20px);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  .animate-slide-in-right {
    animation: slideInRight 0.5s ease-out forwards;
  }
</style>
@endpush