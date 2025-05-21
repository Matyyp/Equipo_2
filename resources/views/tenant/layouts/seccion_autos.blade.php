@php
    use App\Models\VehicleType;
    $vehicles = VehicleType::with('image')->get();
    $vehicleCount = $vehicles->count();
@endphp

@if($vehicleCount)
<section class="py-16 bg-gray-100">
  <h2 class="text-3xl font-bold text-center mb-8">Nuestros Vehículos Para Tí</h2>
  <div class="container mx-auto px-4 overflow-hidden">
    <div class="relative">
      <!-- Contenedor principal -->
      <div id="vehicleCarousel" class="flex transition-transform duration-500 ease-in-out">
        <!-- Duplicamos los elementos para el efecto circular -->
        @foreach (array_merge($vehicles->toArray(), $vehicles->toArray()) as $v)
        <div class="w-64 px-3 flex-shrink-0">
          <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 h-full">
            <img src="{{ tenant_asset($v['image']['path'] ?? 'img/placeholder.jpg') }}"
                 alt="{{ $v['card_title'] ?? '' }}"
                 class="w-full h-48 object-cover">
            <div class="p-4"
                 style="background-color: {{ $v['card_background_color'] ?? '#f9fafb' }}; color: {{ $v['text_color'] ?? '#111827' }};">
              @if ($v['card_title_active'] ?? false)
                <h3 class="text-lg font-semibold mb-1">{{ $v['card_title'] ?? '' }}</h3>
              @endif
              @if (($v['card_subtitle_active'] ?? false) && ($v['card_subtitle'] ?? false))
                <p class="text-sm opacity-80">{{ $v['card_subtitle'] }}</p>
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Controles de navegación -->
      @if($vehicleCount > 4)
      <button id="prevBtn" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white p-3 rounded-full shadow-lg z-10 hover:bg-gray-100 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <button id="nextBtn" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white p-3 rounded-full shadow-lg z-10 hover:bg-gray-100 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      @endif
    </div>
  </div>
</section>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
  const carousel = document.getElementById('vehicleCarousel');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const items = carousel.children;
  const itemWidth = items[0].offsetWidth;
  const itemsToShow = 4;
  const vehicleCount = {{ $vehicleCount }};
  
  if(vehicleCount <= itemsToShow) {
    // Si hay 4 o menos, mostramos todos sin animación
    carousel.style.justifyContent = 'center';
    return;
  }
  
  // Configuración del carrusel circular
  let currentIndex = 0;
  const totalItems = vehicleCount;
  const clonedItems = vehicleCount; // Cantidad de items clonados
  
  function updateCarousel() {
    const newPosition = -currentIndex * itemWidth;
    carousel.style.transform = `translateX(${newPosition}px)`;
    
    // Efecto circular: cuando llegamos al final, saltamos sin animación al principio
    if(currentIndex >= totalItems) {
      currentIndex = 0;
      setTimeout(() => {
        carousel.style.transition = 'none';
        carousel.style.transform = `translateX(0)`;
        setTimeout(() => {
          carousel.style.transition = 'transform 500ms ease-in-out';
        }, 20);
      }, 500);
    }
  }
  
  // Navegación
  if(prevBtn) {
    prevBtn.addEventListener('click', function() {
      currentIndex = (currentIndex - 1 + totalItems) % totalItems;
      updateCarousel();
    });
  }
  
  if(nextBtn) {
    nextBtn.addEventListener('click', function() {
      currentIndex = (currentIndex + 1) % (totalItems + 1);
      updateCarousel();
    });
  }
  
  // Autoplay
  if(vehicleCount > itemsToShow) {
    let autoplay = setInterval(() => {
      currentIndex = (currentIndex + 1) % (totalItems + 1);
      updateCarousel();
    }, 3000);
    
    // Pausar al interactuar
    carousel.addEventListener('mouseenter', () => clearInterval(autoplay));
    carousel.addEventListener('mouseleave', () => {
      autoplay = setInterval(() => {
        currentIndex = (currentIndex + 1) % (totalItems + 1);
        updateCarousel();
      }, 3000);
    });
  }
  
  // Inicializar
  updateCarousel();
});
</script>

<style>
#vehicleCarousel {
  display: flex;
  gap: 12px;
  padding: 10px 0;
}

.carousel-container {
  perspective: 1000px;
}

.carousel-item {
  transition: transform 0.5s ease, opacity 0.5s ease;
}

button {
  transition: all 0.3s ease;
}

button:hover {
  transform: scale(1.1);
}

button:disabled {
  opacity: 0.3;
  cursor: not-allowed;
  transform: none;
}
</style>