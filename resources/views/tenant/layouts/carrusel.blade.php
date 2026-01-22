@php
    use App\Models\ContainerImageLanding;
    $images = ContainerImageLanding::all();
@endphp

<section class="py-16 bg-white">
  <div class="container mx-auto px-4 text-center">
    <h2 class="text-3xl font-bold mb-10">Explora la Patagonia en imágenes</h2>
    
    @if($images->count() === 1)
      <div class="single-image">
        <img src="{{ tenant_asset($images[0]->path) }}" 
             alt="Imagen de Patagonia" 
             class="w-full max-w-5xl mx-auto h-[400px] md:h-[600px] lg:h-[700px] object-cover rounded-xl shadow-lg">
      </div>
    @elseif($images->count() > 1)
      <div class="swiper patagonia-gallery-carousel relative overflow-hidden group w-full mx-auto">
        <div class="swiper-wrapper">
          @foreach ($images as $image)
            <div class="swiper-slide">
              <img src="{{ tenant_asset($image->path) }}" 
                   alt="Imagen de Patagonia" 
                   class="w-full h-[450px] md:h-[650px] lg:h-[750px] object-cover rounded-xl shadow-lg">
            </div>
          @endforeach
        </div>
        
        <div class="swiper-button-next text-orange-500 after:text-3xl"></div>
        <div class="swiper-button-prev text-orange-500 after:text-3xl"></div>
        
        <div class="swiper-pagination !-bottom-2"></div>
      </div>
    @else
      <div class="border-2 border-dashed border-gray-200 rounded-xl py-20">
        <p class="text-gray-500 text-lg">Agrega imágenes desde el panel de administración</p>
      </div>
    @endif
  </div>
</section>

@push('scripts')
@if($images->count() > 1)
<script>
  document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.patagonia-gallery-carousel', {
      loop: true,
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: 1, 
      spaceBetween: 0,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      effect: 'slide',
      speed: 1000, // Un poco más lento para apreciar la altura
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      }
    });
  });
</script>
@endif
@endpush