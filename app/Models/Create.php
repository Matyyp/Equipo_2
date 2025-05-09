<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Create extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_ticket',
        'id_parking_register'
    ];

    public function create_parking_register()
    {
        return $this->belongsTo(ParkingRegister::class, 'id_parking_register', 'id_parking_register');
    }

    public function create_ticket_parking()
    {
        return $this->belongsTo(TicketParking::class, 'id_ticket', 'id_ticket');
    }
}
