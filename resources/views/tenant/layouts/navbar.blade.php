@php
    $navbar = \App\Models\Navbar::first();
@endphp

<!-- Barra superior -->
<div class="hidden lg:flex text-sm px-6 py-2 flex-wrap justify-around items-center font-light"
     style="background-color: {{ $navbar->background_color_1 ?? '#000000' }}">
    <div class="flex flex-wrap gap-6 items-center text-[15px]">
        
        {{-- Reservas --}}
        @if ($navbar->reservations_active)
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" style="color: {{ $navbar->text_color_1 }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2 8.5C2 6 4 4 6.5 4S11 6 11 8.5 9 13 6.5 13 2 11 2 8.5Zm0 0v6a2 2 0 0 0 2 2h1"/>
                </svg>
                <span>
                    <span class="font-semibold" style="color: {{ $navbar->text_color_1 }}">Reservas:</span>
                    <span style="color: {{ $navbar->text_color_2 }}">{{ $navbar->reservations }}</span>
                </span>
            </div>
        @endif

        {{-- Horario --}}
        @if ($navbar->schedule_active)
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" style="color: {{ $navbar->text_color_1 }}">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 6v6l4 2" />
                </svg>
                <span>
                    <span class="font-semibold" style="color: {{ $navbar->text_color_1 }}">Horario:</span>
                    <span style="color: {{ $navbar->text_color_2 }}">{{ $navbar->schedule }}</span>
                </span>
            </div>
        @endif

        {{-- Correo --}}
        @if ($navbar->email_active)
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" style="color: {{ $navbar->text_color_1 }}">
                    <path d="M4 4h16v16H4z" />
                    <path d="M4 4l8 8 8-8" />
                </svg>
                <span>
                    <span class="font-semibold" style="color: {{ $navbar->text_color_1 }}">Correo:</span>
                    <span style="color: {{ $navbar->text_color_2 }}">{{ $navbar->email }}</span>
                </span>
            </div>
        @endif

        {{-- Dirección --}}
        @if ($navbar->address_active)
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" style="color: {{ $navbar->text_color_1 }}">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Z" />
                    <circle cx="12" cy="9" r="2.5" />
                </svg>
                <span>
                    <span class="font-semibold" style="color: {{ $navbar->text_color_1 }}">Dirección:</span>
                    <span style="color: {{ $navbar->text_color_2 }}">{{ $navbar->address }}</span>
                </span>
            </div>
        @endif
    </div>
</div>

<!-- Navbar principal -->
<nav class="px-6 py-4 relative z-50"
     style="background-color: {{ $navbar->background_color_2 ?? '#111111' }}; color: {{ $navbar->text_color_2 }}">
    <div class="flex justify-between items-center">
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
        </a>    

        <!-- Botón móvil -->
        <div class="md:hidden">
            <button id="menu-toggle" class="focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Menú -->
        <ul id="menu" class="hidden md:flex absolute md:static top-full left-0 w-full md:w-auto
            bg-gray-900 md:bg-transparent flex-col md:flex-row gap-0 md:gap-8 z-40 transition-all duration-200">

            {{-- Servicios --}}
            @php
            $serviceItems = array_filter(array_map('trim', explode(',', $navbar->services)));
            @endphp

            @if ($navbar->services_active && count($serviceItems))
            <li x-data="{ open: false }" @click.away="open = false" class="w-full md:w-auto relative">
                <button @click.prevent="open = !open"
                    class="flex items-center w-full text-left px-4 py-2 hover:text-orange-400 bg-gray-800 md:bg-transparent rounded-none md:rounded focus:outline-none">
                    Servicios
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <ul x-show="open" x-cloak x-transition
                    class="absolute mt-1 left-0 bg-gray-900 md:min-w-[180px] shadow-lg rounded overflow-hidden z-50">
                    @foreach ($serviceItems as $item)
                        @php
                            $lower = Str::lower($item);
                            $route = match (true) {
                                str_contains($lower, 'arrienda') => 'landings.available',
                                //str_contains($lower, 'agenda estacionamiento') => 'landings.park',
                                default => null,
                            };
                        @endphp
                        <li>
                            <a href="{{ $route ? route($route) : '#' }}"
                            class="block px-4 py-2 text-gray-200 hover:bg-gray-800 hover:text-white">
                            {{ $item }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            @endif


            {{-- Quiénes somos --}}
            @if ($navbar->about_us_active)
            <li class="w-full md:w-auto">
                <a href="#about-us" class="block w-full text-left px-4 py-2 hover:text-orange-400">
                    {{ $navbar->about_us }}
                </a>
            </li>
            @endif

            {{-- Contáctanos --}}
            @if ($navbar->contact_us_active)
            <li class="w-full md:w-auto">
                <a href="#contacto" class="block w-full text-left px-4 py-2 hover:text-orange-400">
                    {{ $navbar->contact_us }}
                </a>
            </li>
            @endif

            {{-- Opciones responsive para guest --}}
            @guest
                @if ($navbar->button_1_active)
                    <li class="w-full md:hidden">
                        <a href="{{ route('register') }}"
                           class="block w-full px-4 py-2 text-center rounded"
                           style="background-color: {{ $navbar->button_color_1 }}; color: #000">
                            {{ $navbar->button_1 }}
                        </a>
                    </li>
                @endif
                @if ($navbar->button_2_active)
                    <li class="w-full md:hidden mt-4">
                        <a href="{{ route('login') }}"
                           class="block w-full px-4 py-2 text-center rounded"
                           style="background-color: {{ $navbar->button_color_2 }}; color: white">
                            {{ $navbar->button_2 }}
                        </a>
                    </li>
                @endif
            @else
                {{-- Admin Panel y Logout en móvil --}}
                @can('admin.panel.access')
                    <li class="w-full md:hidden">
                        <a href="{{ route('dashboard') }}"
                           class="block w-full px-4 py-2 bg-gray-700 rounded hover:bg-gray-600 text-center">
                            Admin Panel
                        </a>
                    </li>
                @endcan
                <li class="w-full md:hidden mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="block w-full text-center px-4 py-2 bg-red-600 rounded hover:bg-red-700">
                            Cerrar sesión
                        </button>
                    </form>
                </li>
            @endguest
        </ul>

        <!-- Botones para pantallas md+ -->
        <div class="hidden md:flex gap-3 items-center">
            @guest
                @if ($navbar->button_1_active)
                    <a href="{{ route('register') }}"
                       class="px-4 py-1 rounded"
                       style="background-color: {{ $navbar->button_color_1 }}; color: #000">
                        {{ $navbar->button_1 }}
                    </a>
                @endif
                @if ($navbar->button_2_active)
                    <a href="{{ route('login') }}"
                       class="px-4 py-1 rounded"
                       style="background-color: {{ $navbar->button_color_2 }}; color: white">
                        {{ $navbar->button_2 }}
                    </a>
                @endif
            @else
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 px-4 py-1 hover:text-orange-400">
                        {{ Auth::user()->name }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="open" x-transition
                        class="absolute right-0 mt-2 bg-white text-black shadow-lg rounded-md overflow-hidden z-50 w-40">
                        @can('admin.panel.access')
                            <li>
                                <button type="button"
                                        onclick="window.location='{{ route('dashboard') }}'"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-200">
                                    Admin Panel
                                </button>
                            </li>
                        @endcan
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-200">
                                    Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>
    </div>
</nav>
