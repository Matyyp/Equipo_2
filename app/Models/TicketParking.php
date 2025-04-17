<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketParking extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'status',
    ];

    public function ticket_parking_create()
    {
        return $this->hasMany(Create::class, 'id_ticket', 'id_ticket');
    }
}
