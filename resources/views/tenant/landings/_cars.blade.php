{{-- Este partial recibe $cars y monta exactamente las tarjetas --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
  @forelse($cars as $car)
    <div class="bg-white shadow rounded overflow-hidden">
      @if($car->images->isNotEmpty())
        <div class="swiper-auto relative">
          <div class="swiper-wrapper">
            @foreach($car->images as $img)
              <div class="swiper-slide">
                <img src="{{ tenant_asset($img->path) }}"
                     alt="{{ $car->brand->name_brand }} {{ $car->model->name_model }}"
                     class="w-full h-48 object-cover">
              </div>
            @endforeach
          </div>
          <div class="swiper-button-prev"
               style="position:absolute; top:50%; left:10px; transform:translateY(-50%); z-index:10; color:#fff;"></div>
          <div class="swiper-button-next"
               style="position:absolute; top:50%; right:10px; transform:translateY(-50%); z-index:10; color:#fff;"></div>
          <div class="swiper-pagination" style="bottom:8px;"></div>
        </div>
      @else
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
          <span class="text-gray-500">Sin imagen</span>
        </div>
      @endif

      <div class="p-4">
        <h3 class="text-lg font-semibold">
          {{ $car->brand->name_brand }} {{ $car->model->name_model }}
        </h3>
        <p class="text-sm text-gray-600">Año: {{ $car->year }}</p>
      </div>
    </div>
  @empty
    <div class="col-span-full text-center text-gray-500 py-12">
      No se encontraron autos disponibles.
    </div>
  @endforelse
</div>
