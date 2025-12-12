@extends('tenant.layouts.landings')

@section('content')
<div class="max-w-xl mx-auto py-12">
    <h2 class="text-2xl font-bold text-center text-green-600 mb-6">¡Solictud de reserva fue un exitoso!</h2>

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
                <span class="font-semibold">Nos comunicaremos con usted para completar la solicitud!</span>
            </div>

        </div>

        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg">
                Volver al inicio
            </a>
        </div>
    </div>
</div>
@endsection
