<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRegister extends Model
{
    use HasFactory;

    // Nombre de la tabla y PK personalizado
    protected $table      = 'payment_records';
    protected $primaryKey = 'id_payment';
    public $incrementing  = true;
    protected $keyType    = 'int';

    // Campos rellenables con create()
    protected $fillable = [
        'id_service',
        'id_voucher',
        'amount',
        'type_payment',
        'payment_date',
    ];

    // Casteos para fechas y montos
    protected $casts = [
        'payment_date' => 'datetime',
        'amount'       => 'decimal:2',
    ];

    // Relaciones
    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_service');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'id_voucher', 'id_voucher');
    }
    public function payments()
    {
        return $this->belongsToMany(
            PaymentRegister::class,
            'payment_records',   // nombre de la tabla pivote
            'id_register',       // FK en pivote que apunta a registers.id
            'id_payment'         // FK en pivote que apunta a payment_registers.id_payment
        );
    }
}