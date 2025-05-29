@php
    use App\Models\ServiceLanding;
    $services = ServiceLanding::all();
@endphp

@if($services->count() > 0)
    @foreach($services as $key => $service)
        @php
            $hasImage = isset($service->image) && $service->image && $service->image->path;
            $hasContent = ($service->title_active && $service->title) || 
                         ($service->secondary_text_active && $service->secondary_text) ||
                         ($service->small_text_active && $service->small_text);
        @endphp

        @if($hasContent || $hasImage)
            <!-- Sección de servicio -->
            <section class="py-20 {{ $key % 2 == 0 ? 'bg-gray-50' : 'bg-gray-100' }}">
                <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                    @if($key % 2 == 0 && $hasImage)
                        <!-- Imagen a la izquierda para elementos impares (índice 0-based) -->
                        <div data-aos="fade-right" class="overflow-hidden rounded-2xl shadow-xl border-4" style="border-color: {{ $service->card_background_color }};">
                            <img src="{{ tenant_asset($service->image->path) }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    @if($hasContent)
                        <div data-aos="{{ $key % 2 == 0 ? 'fade-left' : 'fade-right' }}">
                            <div class="rounded-2xl shadow-2xl p-6 space-y-4" style="background-color: {{ $service->card_background_color }};">
                                @if($service->title_active && $service->title)
                                    <h3 class="text-2xl font-bold" style="color: {{ $service->title_color }};">{{ $service->title }}</h3>
                                @endif
                                
                                @if($service->secondary_text_active && $service->secondary_text)
                                    <p class="text-lg leading-relaxed" style="color: {{ $service->secondary_text_color }};">{{ $service->secondary_text }}</p>
                                @endif
                                
                                @if($service->small_text_active && $service->small_text)
                                    <p class="text-sm italic" style="color: {{ $service->small_text_color }};">{{ $service->small_text }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($key % 2 != 0 && $hasImage)
                        <!-- Imagen a la derecha para elementos pares (índice 0-based) -->
                        <div data-aos="fade-left" class="overflow-hidden rounded-2xl shadow-xl border-4" style="border-color: {{ $service->card_background_color }};">
                            <img src="{{ tenant_asset($service->image->path) }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                </div>
            </section>
        @endif
    @endforeach
@endif