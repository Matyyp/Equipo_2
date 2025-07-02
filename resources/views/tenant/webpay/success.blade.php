@extends('tenant.layouts.landings')

@section('content')
<div class="max-w-xl mx-auto py-12">
    <h2 class="text-2xl font-bold text-center text-green-600 mb-6">¡Pago exitoso!</h2>

    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="border rounded-lg p-4 bg-gray-50 space-y-4">

            <div>
                <span class="font-semibold">Sucursal de retiro:</span>
                {{ $reservation->branchOffice->name_branch_offices ?? 'Sucursal no encontrada' }}
            </div>

            <div>
                <span class="font-semibold">Fecha de reserva:</span>
                {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}
                al
                {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}
            </div>

            <div>
                <span class="font-semibold">Total:</span>
                ${{ number_format($payment->amount, 0, ',', '.') }}
            </div>

            <div>
                <span class="font-semibold">Número de orden:</span>
                {{ $payment->buy_order ?? 'No disponible' }}
            </div>
        </div>

        <div class="mt-6 p-4 bg-info border-l-4 border-yellow-500 rounded">
            <p class="text-sm">
                <strong>Recuerda:</strong> al momento de retirar el vehículo, se solicitarán datos personales adicionales y 
                <strong>la garantía deberá pagarse únicamente con tarjeta de crédito</strong>. 
                No olvides presentar tu <strong>cédula de identidad</strong> y <strong>licencia de conducir vigente</strong>.
            </p>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg">
                Volver al inicio
            </a>
        </div>
    </div>
</div>
@endsection
