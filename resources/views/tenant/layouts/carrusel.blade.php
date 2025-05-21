@php
    use App\Models\ContainerImageLanding;

    $images = ContainerImageLanding::all(); // O puedes agregar algún filtro si lo necesitas
@endphp

<!-- Sección de Galería -->
<section class="py-16 bg-white">
  <div class="container mx-auto px-4 text-center">
    <h2 class="text-3xl font-bold mb-10">Explora la Patagonia en imágenes</h2>
    
    @if($images->count() === 1)
      <!-- Mostrar imagen estática si solo hay una imagen -->
      <div class="single-image">
        <img src="{{ tenant_asset(''.$images[0]->path) }}" 
             alt="Imagen de Patagonia" 
             class="w-full max-w-4xl mx-auto h-[300px] md:h-[400px] object-cover rounded-xl shadow-lg">
      </div>
    @elseif($images->count() > 1)
      <!-- Mostrar carrusel si hay múltiples imágenes -->
      <div class="swiper patagonia-gallery-carousel relative overflow-hidden">
        <div class="swiper-wrapper">
          @foreach ($images as $image)
            <div class="swiper-slide">
              <img src="{{ tenant_asset(''.$image->path) }}" 
                   alt="Imagen de Patagonia" 
                   class="w-full h-[300px] md:h-[400px] object-cover rounded-xl shadow-lg">
            </div>
          @endforeach
        </div>
        <div class="swiper-button-next text-orange-500"></div>
        <div class="swiper-button-prev text-orange-500"></div>
        <div class="swiper-pagination mt-4"></div>
      </div>
    @else
      <!-- Mensaje si no hay imágenes -->
      <p class="text-gray-500 py-10">Próximamente más imágenes de la Patagonia</p>
    @endif
  </div>
</section>

@push('scripts')
@if($images->count() > 1)
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Configuración específica para este carrusel
    new Swiper('.patagonia-gallery-carousel', {
      loop: true,
      autoplay: {
        delay: 5000,
        pauseOnMouseEnter: true,
      },
      effect: 'slide',
      grabCursor: true,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true,
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
          spaceBetween: 15
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 30
        }
      }
    });
  });
</script>
@endif
@endpush