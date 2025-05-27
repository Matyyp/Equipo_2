@extends('tenant.layouts.landings')

@section('content')
<div class="max-w-xl mx-auto py-12">
    <h2 class="text-2xl font-bold text-center text-red-600 mb-6">❌ Pago rechazado</h2>

    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="border rounded-lg p-4 bg-red-50 space-y-4 text-red-800">

            <p class="font-semibold">Lamentablemente tu pago fue rechazado.</p>
            <p>No se ha generado ninguna reserva debido a este fallo en la transacción.</p>
            <p>Si crees que esto es un error, por favor intenta nuevamente o contacta con el soporte.</p>

        </div>

        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg">
                Volver al comercio
            </a>
        </div>
    </div>
</div>
@endsection
