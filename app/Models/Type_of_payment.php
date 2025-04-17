<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_of_payment extends Model
{
    use HasFactory;

    public function type_of_payment_select()
    {
        return $this->hasMany(Select::class, 'id_payment');
    }
}
