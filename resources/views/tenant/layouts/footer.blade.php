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
        <div>
            <img src="{{ tenant_asset('logos/logo.png') }}" class="h-12 mb-2" alt="Logo Footer">
            <p class="text-sm" style="color: {{ $footer->text_color_2 }};">
                {{ $footer->copyright }} © {{ date('Y') }}
            </p>
        </div>

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