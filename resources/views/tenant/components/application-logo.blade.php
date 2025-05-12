{{-- resources/views/tenant/components/application-logo.blade.php --}}
@props(['class'])

@if (! empty($tenantLogo))
    <img
        src="{{ $tenantLogo }}"
        alt="{{ config('app.name') }}"
        {{ $attributes->merge([
            'class' => trim(($class ?? '') . ' max-h-20 w-auto object-contain'),
            'style' => 'display:block; margin:auto;',
        ]) }}
    />
@else
    {{-- SVG por defecto --}}
    <svg {{ $attributes->merge(['class' => $class]) }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 841.9 595.3">
        <g fill="#1c1917"><!-- …paths… --></g>
    </svg>
@endif