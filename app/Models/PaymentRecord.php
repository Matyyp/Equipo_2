<?php

namespace App\Models;

use App\Models\Service;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PaymentRecord extends Model
{
    use HasFactory;

    protected $table = 'payment_records';

    protected $primaryKey = 'id_payment'; // si realmente es la PK
    

    protected $fillable = [
        'id_service',
        'id_parking_register',
        'type_payment',
        'amount',
        'payment_date',
        'id_voucher',
    ];
    

    /**
     * Relación con Payment como "servicio pagado"
     */
    public function paymentService()
    {
        return $this->belongsTo(Payment::class, 'id_service', 'id_service');
    }

    /**
     * Relación con Payment como "voucher utilizado"
     */
    public function paymentVoucher()
    {
        return $this->belongsTo(Payment::class, 'id_voucher', 'id_voucher');
    }
}
