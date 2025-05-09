<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{
    use HasFactory;

    // Nombre de la tabla pivote que une services y vouchers
    protected $table = 'payments';

    // Campos rellenables
    protected $fillable = [
        'id_service',
        'id_voucher',
    ];

    /**
     * Relación con Service
     */
    public function service()
    {
        return $this->belongsTo(
            Service::class,
            'id_service',  // FK en tabla payments
            'id_service'   // PK en tabla services
        );
    }

    /**
     * Relación con Voucher
     */
    public function voucher()
    {
        return $this->belongsTo(
            Voucher::class,
            'id_voucher',  // FK en tabla payments
            'id_voucher'   // PK en tabla vouchers
        );
    }
}