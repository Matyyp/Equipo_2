@component('mail::message')
# ¡Una persona acaba de hacer una solitud de reserva de arriendo!

Ingresa a la plataforma para completar la solicitud y observar los datos de la persona, ademas de que te coloques en contacto:
Informacion de la reserva:
- **Auto:** 
  {{ optional($reservation->car->brand)->name_brand ?? 'Marca no disponible' }} 
  {{ optional($reservation->car->model)->name_model ?? 'Modelo no disponible' }}
- **Sucursal:** {{ $reservation->branchOffice->name_branch_offices ?? 'Información no disponible' }}
- **Fecha de inicio:** {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}
- **Fecha de término:** {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}

¡Gracias por leer la informacion!

Saludos,<br>
{{ $businessName }}
@endcomponent
