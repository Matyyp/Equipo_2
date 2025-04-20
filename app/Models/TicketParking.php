<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketParking extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'status',
    ];

    public function ticket_parking_create()
    {
        return $this->hasMany(Create::class, 'id_ticket', 'id_ticket');
    }
}
