<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentRecord extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_service',
        'id_voucher',
        'id_payment',
        'payment_date',
        'type_payment',
        'amount'
    ];

    public function payment_record_payment()
    {
        return $this->belongsTo(Payment::class, 'id_service', 'id_service');
    }
    
    public function payment_record_payment_dos()
    {
        return $this->belongsTo(Payment::class, 'id_voucher', 'id_voucher');
    }
    
}
