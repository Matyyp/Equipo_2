<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_payment',
    ];

    public function type_of_payment_select()
    {
        return $this->hasMany(Select::class, 'id_payment', 'id_payment');
    }
}
