@component('mail::message')
# ¡Tu reserva ha sido confirmada!

Gracias por confiar en nosotros. Aquí tienes un resumen de tu solicitud, pronto nos pondremos en contacto con usted para completar la solicitud:

- **Auto:** 
  {{ optional($reservation->car->brand)->name_brand ?? 'Marca no disponible' }} 
  {{ optional($reservation->car->model)->name_model ?? 'Modelo no disponible' }}
- **Sucursal:** {{ $reservation->branchOffice->name_branch_offices ?? 'Información no disponible' }}
- **Fecha de inicio:** {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}
- **Fecha de término:** {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}

¡Gracias por preferirnos!

Saludos,<br>
{{ $businessName }}
@endcomponent
