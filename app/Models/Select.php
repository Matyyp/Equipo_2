<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Select extends Model
{
    use HasFactory;

    public function select_user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
    public function select_type_of_payment()
    {
        return $this->belongsTo(TypeOfPayment::class, 'id_payment', 'id_payment');
    }
}
