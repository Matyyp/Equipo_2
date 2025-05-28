{{-- resources/views/tenant/landings/_cars.blade.php --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
  @forelse($cars as $car)
    <div
      class="bg-white rounded-lg shadow-md overflow-hidden transform transition
             hover:shadow-lg hover:-translate-y-1 flex flex-col h-full"
    >
      {{-- Carrusel --}}
      @if($car->images->isNotEmpty())
        <div class="swiper-auto relative h-48">
          <div class="swiper-wrapper h-full">
            @foreach($car->images as $img)
              <div class="swiper-slide h-full">
                <img
                  src="{{ tenant_asset($img->path) }}"
                  alt="{{ $car->brand->name_brand }} {{ $car->model->name_model }}"
                  class="w-full h-full object-cover"
                >
              </div>
            @endforeach
          </div>
          <div class="swiper-button-prev absolute top-1/2 left-2 transform -translate-y-1/2 z-10 text-white"></div>
          <div class="swiper-button-next absolute top-1/2 right-2 transform -translate-y-1/2 z-10 text-white"></div>
          <div class="swiper-pagination absolute bottom-2 left-0 right-0 text-center"></div>
        </div>
      @else
        <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">
          Sin imagen
        </div>
      @endif

      {{-- Detalle e iconos --}}
      <div class="p-4 flex-1 flex flex-col">
        <h3 class="text-xl font-medium text-gray-800 mb-2">
          {{ $car->brand->name_brand }} {{ $car->model->name_model }}
        </h3>
        <p class="text-sm text-gray-500 mb-4">Año: {{ $car->year }}</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-gray-600 mb-4">
          {{-- Pasajeros --}}
          <div class="flex flex-col items-center">
            <i class="fas fa-user-friends fa-lg mb-2"></i>
            <span class="text-sm">{{ $car->passenger_capacity }}</span>
          </div>
          {{-- Transmisión --}}
          <div class="flex flex-col items-center">
            <i class="fas fa-cog fa-lg mb-2"></i>
            <span class="text-sm">{{ ucfirst($car->transmission) }}</span>
          </div>
          {{-- Maletas --}}
          <div class="flex flex-col items-center">
            <i class="fas fa-suitcase-rolling fa-lg mb-2"></i>
            <span class="text-sm">{{ $car->luggage_capacity }}</span>
          </div>
          {{-- Precio --}}
          <div class="flex flex-col items-center">
            <i class="fas fa-dollar-sign fa-lg mb-2"></i>
            @if($car->price_per_day > 0)
              <span class="text-sm">${{ number_format($car->price_per_day, 0, ',', '.') }}</span>
            @else
              <span class="text-sm italic text-gray-400">Consultar</span>
            @endif
          </div>
        </div>

        {{-- Enlace al formulario de reserva --}}
        <a
          href="{{ route('cars.reserve', $car) }}"
          class="mt-auto block text-center py-2 px-4 bg-blue-500 text-white font-semibold rounded-md
                 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 transition"
        >
          Agendar
        </a>
      </div>
    </div>
  @empty
    <div class="col-span-full text-center text-gray-500 py-12">
      No se encontraron autos disponibles.
    </div>
  @endforelse
</div>
