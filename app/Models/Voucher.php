<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'payment',
        'discount',
        'amount',
    ];

    public function voucher_make()
    {
        return $this->hasMany(Make::class, 'id_voucher','id_voucher');
    }
}
