<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationPayment extends Model
{
    use HasFactory;
    protected $table = 'reservation_payment';
    protected $fillable = [
        'reservation_id',
        'token',
        'amount',
        'authorization_code',
        'payment_type',
        'response_code',
        'buy_order'
    ];
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
