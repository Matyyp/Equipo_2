<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;

class ReservationConfirmed extends Mailable
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
        return $this->subject('¡Solictud de reserva enviada!')
                    ->markdown('tenant.emails.reservation.confirmed')
                    ->with([
                        'reservation' => $this->reservation,
                        // 'payment' => $this->payment,
                        'businessName' => $this->businessName,
                        'tenantLogo' => $this->tenantLogo,
                        'tenantCompanyName' => $this->tenantCompanyName,
                    ]);
    }
}

