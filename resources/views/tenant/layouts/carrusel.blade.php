@php
    use App\Models\ContainerImageLanding;
    $images = ContainerImageLanding::all();
@endphp

<style>
  .patagonia-gallery-carousel .swiper-slide img {
    width: 100%;
    height: 600px !important; 
    object-fit: cover; 
    object-position: center;
    border-radius: 0; 
  }

  @media (max-width: 768px) {
    .patagonia-gallery-carousel .swiper-slide img {
      height: 400px !important;
    }
  }

  .swiper-button-next { right: 20px !important; }
  .swiper-button-prev { left: 20px !important; }
</style>

<section class="py-16 bg-white">
  <div class="text-center">
    <h2 class="text-3xl font-bold mb-10">Explora la Patagonia en imágenes</h2>
    
    @if($images->count() === 1)
      <div class="w-full">
        <img src="{{ tenant_asset($images[0]->path) }}" 
             alt="Imagen de Patagonia" 
             class="w-full h-[600px] object-cover shadow-lg">
      </div>
    @elseif($images->count() > 1)
      <div class="swiper patagonia-gallery-carousel relative overflow-hidden w-full shadow-2xl">
        <div class="swiper-wrapper">
          @foreach ($images as $image)
            <div class="swiper-slide">
              <img src="{{ tenant_asset($image->path) }}" 
                   alt="Imagen de Patagonia">
            </div>
          @endforeach
        </div>
        
        <div class="swiper-button-next text-orange-600"></div>
        <div class="swiper-button-prev text-orange-600"></div>
        
        <div class="swiper-pagination"></div>
      </div>
    @else
      <div class="container mx-auto px-4 border-2 border-dashed border-gray-200 rounded-xl py-20">
        <p class="text-gray-500 text-lg">No hay imágenes disponibles.</p>
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
      slidesPerView: 1, 
      spaceBetween: 0,
      grabCursor: true,
      speed: 1200,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
    });
  });
</script>
@endif
@endpush