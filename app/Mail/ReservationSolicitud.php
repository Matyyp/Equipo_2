<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;

class ReservationSolicitud extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    // public $payment;
    public $businessName;
    public $tenantLogo;
    public $tenantCompanyName;

    public function __construct($reservation, $businessName) #$payment se saco de la funcion
    {
        $this->reservation = $reservation;
        // $this->payment = $payment;
        $this->businessName = $businessName;
        $this->tenantLogo = optional(tenant())->logo_url ?? null;
        $this->tenantCompanyName = $businessName;
    }

    public function build()
    {
        $fromAddress = config('mail.from.address'); 
        $fromName = $this->businessName; 

        return $this->from($fromAddress, $fromName)
                    ->subject('¡Una persona acaba enviar una solictud de reserva de arriendo!')
                    ->markdown('tenant.emails.reservation.soli')
                    ->with([
                        'reservation' => $this->reservation,
                        // 'payment' => $this->payment,
                        'businessName' => $this->businessName,
                        'tenantLogo' => $this->tenantLogo,
                        'tenantCompanyName' => $this->tenantCompanyName,
                    ]);
    }
}

