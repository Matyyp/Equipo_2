<script src="https://unpkg.com/lucide@latest"></script>

@php
    use Illuminate\Support\Str;

    $footer = \App\Models\Footer::first();

    $contacts = $footer && $footer->contact_text ? explode(',', $footer->contact_text) : [];
    $socials  = $footer && $footer->social_text ? explode(',', $footer->social_text) : [];

    function getSocialIcon($url) {
        $host = parse_url($url, PHP_URL_HOST) ?? '';
        if (Str::contains($host, 'facebook')) return 'facebook';
        if (Str::contains($host, 'instagram')) return 'instagram';
        if (Str::contains($host, 'twitter') || Str::contains($host, 'x.com')) return 'twitter';
        if (Str::contains($host, 'youtube')) return 'youtube';
        if (Str::contains($host, 'linkedin')) return 'linkedin';
        if (Str::contains($host, 'tiktok')) return 'video';
        return 'globe'; // default icon
    }

    function cleanSocialName($url) {
        $host = parse_url($url, PHP_URL_HOST) ?? $url;
        $host = preg_replace('/^www\./', '', $host);
        return ucfirst(explode('.', $host)[0]);
    }
@endphp

@if($footer)
<footer class="py-8" style="background-color: {{ $footer->background_color }};">
    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row justify-between gap-6">

        {{-- Logo + Copyright --}}
        <a href="/"
            class="brand-link d-flex justify-content-center align-items-center">
                @if (! empty($tenantLogo))
                    <img
                        src="{{ $tenantLogo }}"
                        alt="Sube tu Logo "
                        class="brand-image" 
                        style="display:block; margin:0 auto; max-height:50px; width:auto;"
                    />
                @else
                    <span class="brand-text font-weight-light">
                        {{ $tenantCompanyName ?? config('app.name') }}
                    </span>
                @endif
                            <p class="text-sm" style="color: {{ $footer->text_color_2 }};">
                {{ $footer->copyright }} © {{ date('Y') }}
            </p>
        </a>  

        {{-- Contacto --}}
        @if($footer->contact_active && count($contacts))
        <div>
            <h3 class="font-semibold mb-2" style="color: {{ $footer->text_color_1 }};">Contacto</h3>
            <ul class="text-sm space-y-1">
                @foreach($contacts as $contact)
                    <li class="flex items-center gap-2">
                        <i data-lucide="mail" style="color: {{ $footer->text_color_1 }};" class="w-4 h-4"></i>
                        <span style="color: {{ $footer->text_color_2 }};">{{ trim($contact) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Redes Sociales --}}
        @if($footer->social_active && count($socials))
        <div>
            <h3 class="font-semibold mb-2" style="color: {{ $footer->text_color_1 }};">Redes Sociales</h3>
            <ul class="text-sm space-y-1">
                @foreach($socials as $social)
                    @php
                        $url = trim($social);
                        $icon = getSocialIcon($url);
                        $name = cleanSocialName($url);
                    @endphp
                    <li class="flex items-center gap-2">
                        <i data-lucide="{{ $icon }}" style="color: {{ $footer->text_color_1 }};" class="w-4 h-4"></i>
                        <a href="{{ $url }}" target="_blank" class="hover:underline" style="color: {{ $footer->text_color_2 }};">
                            {{ $name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</footer>
@endif



<!-- Inicializar los iconos -->
<script>
    lucide.createIcons();
</script>