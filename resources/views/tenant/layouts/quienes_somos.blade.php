@php
    $aboutUs = \App\Models\AboutUs::first();
    $videos = $aboutUs->video_links ? array_filter(explode(',', $aboutUs->video_links)) : [];

    // Obtener la URL del primer video en formato embed
    $firstVideo = '';
    if (count($videos) > 0) {
        $first = trim($videos[0]);
        if (str_contains($first, 'youtube.com')) {
            if (str_contains($first, 'embed')) {
                $firstVideo = $first;
            } else {
                parse_str(parse_url($first, PHP_URL_QUERY), $params);
                $videoId = $params['v'] ?? '';
                $firstVideo = $videoId ? "https://www.youtube.com/embed/{$videoId}" : '';
            }
        } else {
            $firstVideo = $first;
        }
    }

    $showSection = $aboutUs->top_text_active || $aboutUs->main_title_active || 
                   $aboutUs->secondary_text_active || $aboutUs->tertiary_text_active;
@endphp

@if($showSection)
  <!-- About Us Section -->
  <section id='about-us' class="py-16 bg-white">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-10">
      @if(count($videos) > 0)
        <div data-aos="fade-right" class="flex flex-col">
          <!-- Contenedor de botones de video con color de fondo -->
          <div class="flex gap-2 mb-4 p-3 rounded-lg">
            @foreach($videos as $index => $video)
              @php
                $video = trim($video);
                if (str_contains($video, 'youtube.com')) {
                    if (str_contains($video, 'embed')) {
                        $embedUrl = $video;
                    } else {
                        parse_str(parse_url($video, PHP_URL_QUERY), $params);
                        $videoId = $params['v'] ?? '';
                        $embedUrl = $videoId ? "https://www.youtube.com/embed/{$videoId}" : $video;
                    }
                } else {
                    $embedUrl = $video;
                }
              @endphp
              <button onclick="setVideo('{{ $embedUrl }}')" 
                      class="px-4 py-2 rounded-xl text-sm font-semibold shadow-md transition duration-300"
                      style="background-color: {{ $aboutUs->video_card_color }}; color: {{ $aboutUs->card_text_color }};">
                Video {{ $index + 1 }}
              </button>
            @endforeach
          </div>
          
          <!-- Contenedor del iframe del video -->
          <div class="aspect-video rounded-2xl overflow-hidden shadow-xl border-4" style="border-color: {{ $aboutUs->video_card_color }}; background-color: {{ $aboutUs->video_card_color }};">
            <iframe id="videoFrame" src="{{ $firstVideo }}" frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen
              class="w-full h-full"></iframe>
          </div>
        </div>
      @endif

      <!-- Sección de texto -->
      <div data-aos="fade-left" class="flex items-center">
        <div class="rounded-2xl shadow-lg p-8 space-y-5 w-full" style="background-color: {{ $aboutUs->card_color }}; color: {{ $aboutUs->card_text_color }};">
          @if($aboutUs->top_text_active && $aboutUs->top_text)
            <p class="uppercase text-sm opacity-80 tracking-wider">{{ $aboutUs->top_text }}</p>
          @endif
          
          @if($aboutUs->main_title_active && $aboutUs->main_title)
            <h2 class="text-3xl font-bold" style="color: {{ $aboutUs->button_color }}">{{ $aboutUs->main_title }}</h2>
          @endif
          
          @if($aboutUs->secondary_text_active && $aboutUs->secondary_text)
            <p class="text-base leading-relaxed opacity-90">{{ $aboutUs->secondary_text }}</p>
          @endif
          
          @if($aboutUs->tertiary_text_active && $aboutUs->tertiary_text)
            <p class="text-base leading-relaxed opacity-90">{{ $aboutUs->tertiary_text }}</p>
          @endif
          
          @if($aboutUs->button_active && $aboutUs->button_text)
            <a href="{{ $aboutUs->button_link ?: '#' }}" 
               class="mt-4 px-6 py-2 rounded-xl font-semibold shadow-md inline-block transition duration-300"
               style="background-color: {{ $aboutUs->button_color }}; color: {{ $aboutUs->button_text_color }};">
              {{ $aboutUs->button_text }} →
            </a>
          @endif
        </div>
      </div>
    </div>
  </section>

  <script>
    function setVideo(url) {
      document.getElementById('videoFrame').src = url;
    }
  </script>
@endif
