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
    public $payment;
    public $businessName;

    public function __construct($reservation, $payment, $businessName)
    {
        $this->reservation = $reservation;
        $this->payment = $payment;
        $this->businessName = $businessName;
    }

    public function build()
    {
        return $this->subject('¡Reserva confirmada!')
                    ->markdown('tenant.emails.reservation.confirmed')
                    ->with([
                        'reservation' => $this->reservation,
                        'payment' => $this->payment,
                        'businessName' => $this->businessName,
                    ]);
    }
}

