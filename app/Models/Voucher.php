<?php

namespace App\Models;

use App\Models\PaymentRegister;
use App\Models\Make;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_voucher';
    // protected $table = 'vouchers'; // sólo si usas un nombre distinto

    protected $fillable = [
        'code',
        'payment',
        'discount',
        'amount',
    ];

    public function voucher_make()
    {
        return $this->hasMany(Make::class, 'id_voucher', 'id_voucher');
    }

    public function paymentRegisters()
    {
        return $this->hasMany(PaymentRegister::class, 'id_voucher', 'id_voucher');
    }
}
