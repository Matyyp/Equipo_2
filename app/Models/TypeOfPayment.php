<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeOfPayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name_payment',
    ];

    public function type_of_payment_select()
    {
        return $this->hasMany(Select::class, 'id_payment', 'id_payment');
    }
}
