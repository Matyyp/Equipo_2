{{-- resources/views/tenant/landings/reserve.blade.php --}}
@extends('tenant.layouts.landings')

@section('title','Reservar '.$car->brand->name_brand.' '.$car->model->name_model)
@push('styles')
  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
@section('content')
@php
    // Obtener rangos de reservas para JS
    $reservedRanges = $car->reservations()
        ->whereIn('status', ['pending', 'confirmed'])
        ->get(['start_date', 'end_date']);
@endphp

<div class="container mx-auto py-12 px-4">
  <h2 class="text-3xl font-semibold mb-8">
    Reservar {{ $car->brand->name_brand }} {{ $car->model->name_model }}
  </h2>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    {{-- IZQUIERDA: Carrusel + características --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      @if($car->images->isNotEmpty())
        <div class="swiper reserve-swiper relative h-80">
          <div class="swiper-wrapper h-full">
            @foreach($car->images as $img)
              <div class="swiper-slide h-full">
                <img
                  src="{{ tenant_asset($img->path) }}"
                  alt="{{ $car->brand->name_brand }} {{ $car->model->name_model }}"
                  class="w-full h-full object-cover"
                >
              </div>
            @endforeach
          </div>
          <div class="swiper-button-prev absolute top-1/2 left-2 -translate-y-1/2 z-10 text-white"></div>
          <div class="swiper-button-next absolute top-1/2 right-2 -translate-y-1/2 z-10 text-white"></div>
          <div class="swiper-pagination absolute bottom-2 left-0 right-0 text-center"></div>
        </div>
      @else
        <div class="w-full h-64 bg-gray-100 flex items-center justify-center text-gray-400">
          Sin imagen
        </div>
      @endif

      <div class="p-4">
        <h3 class="text-2xl font-medium">{{ $car->brand->name_brand }} {{ $car->model->name_model }}</h3>
        <p class="text-sm text-gray-500 mb-6">Año: {{ $car->year }}</p>

        <div class="grid grid-cols-2 gap-4 text-gray-600">
          <div class="flex flex-col items-center">
            <i class="fas fa-user-friends fa-lg mb-2"></i>
            <span class="text-sm">{{ $car->passenger_capacity }}</span>
          </div>
          <div class="flex flex-col items-center">
            <i class="fas fa-cog fa-lg mb-2"></i>
            <span class="text-sm">{{ ucfirst($car->transmission) }}</span>
          </div>
          <div class="flex flex-col items-center">
            <i class="fas fa-suitcase-rolling fa-lg mb-2"></i>
            <span class="text-sm">{{ $car->luggage_capacity }}</span>
          </div>
          <div class="flex flex-col items-center">
            <i class="fas fa-dollar-sign fa-lg mb-2"></i>
            @if($car->price_per_day > 0)
              <span class="text-sm">${{ number_format($car->price_per_day, 0, ',', '.') }} / día</span>
            @else
              <span class="text-sm italic text-gray-400">Consultar</span>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- DERECHA: Formulario --}}
    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col">
      <form action="{{ route('webpay.init', $car) }}" method="POST" class="space-y-4 flex-1">
        @csrf

        {{-- RUT --}}
        <div>
          <label for="rut" class="block font-medium text-gray-700">RUT</label>
          <input
            type="text"
            name="rut"
            id="rut"
            value="{{ old('rut') }}"
            class="w-full border rounded p-2 @error('rut') border-red-500 @enderror"
            placeholder="12345678-9"
            maxlength="10"
            inputmode="numeric"
            required
          >
          @error('rut')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>

        {{-- Nombre --}}

        {{-- Email --}}
        <div>
          <label for="email" class="block font-medium text-gray-700">Correo electrónico</label>
          <input
            type="email"
            name="email"
            id="email"
            value="{{ Auth::user()->email }}"
            class="w-full border rounded p-2 bg-gray-100"
            readonly
          >
        </div>

        {{-- Sucursal (preseleccionada y no editable) --}}
        <div>
          <label class="block font-medium text-gray-700">Sucursal de retiro</label>
          <input
            type="text"
            class="w-full border rounded p-2 bg-gray-100 cursor-not-allowed"
            value="{{ $branches->get($selectedBranchId) }}"
            readonly
          >
          <input
            type="hidden"
            name="branch_office_id"
            value="{{ $selectedBranchId }}"
          >
        </div>

        {{-- Fecha inicio --}}
        <div>
          <label for="start_date" class="block font-medium text-gray-700">Fecha inicio</label>
          <input
            type="text"
            name="start_date"
            id="start_date"
            value="{{ old('start_date') }}"
            class="w-full border rounded p-2 @error('start_date') border-red-500 @enderror"
            placeholder="Selecciona fecha"
            required
          >
          @error('start_date')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>

        {{-- Fecha término --}}
        <div>
          <label for="end_date" class="block font-medium text-gray-700">Fecha término</label>
          <input
            type="text"
            name="end_date"
            id="end_date"
            value="{{ old('end_date') }}"
            class="w-full border rounded p-2 @error('end_date') border-red-500 @enderror"
            placeholder="Selecciona fecha"
            required
          >
          @error('end_date')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>

        <button
            type="submit"
            class="mt-auto w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition"
        >
            Confirmar reserva
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <!-- Flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Inicializa Swiper (igual que antes)
      new Swiper('.reserve-swiper', {
        loop: true,
        effect: 'fade',
        autoplay: { delay: 5000 },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      });

      // Rango de fechas ya reservadas desde el backend
      const reservedRanges = @json($reservedRanges);
      // Prepara el formato que Flatpickr espera
      const disabledDates = reservedRanges.map(r => ({
        from: r.start_date,
        to:   r.end_date
      }));

      // Configuración común para ambos pickers
      const commonConfig = {
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: disabledDates,
      };

      // Inicializa flatpickr en fecha de inicio
      const startPicker = flatpickr("#start_date", {
        ...commonConfig,
        onChange(selectedDates, dateStr) {
          // Al elegir inicio, ajusta mínimo en el picker de término
          endPicker.set('minDate', dateStr);
        }
      });

      // Inicializa flatpickr en fecha de término
      const endPicker = flatpickr("#end_date", {
        ...commonConfig,
      });
    });
  </script>

  <script>
    // Formateo automático de RUT
    const rutInput = document.getElementById('rut');

    // Función que normaliza: quita puntos, guiones, todo NO dígito ni 'K',
    // y luego inserta un único guión antes del último carácter.
    function formatRut(value) {
      // Mayúsculas y solo dígitos/K
      let clean = value.toUpperCase().replace(/[^0-9K]/g, '');
      if (clean.length <= 1) {
        return clean;
      }
      const dv   = clean.slice(-1);
      const body = clean.slice(0, -1);
      return `${body}-${dv}`;
    }

    // Al escribir (input) y al salir (blur) se formatea
    rutInput.addEventListener('input', e => {
      e.target.value = formatRut(e.target.value);
    });
    rutInput.addEventListener('blur', e => {
      e.target.value = formatRut(e.target.value);
    });
  </script>
@endpush
