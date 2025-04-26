<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_service',
        'id_voucher'
    ];

    public function payment_service()
    {
        return $this->belongsTo(Voucher::class, 'id_service', 'id_service');
    }
    
    public function payment_voucher()
    {
        return $this->belongsTo(Service::class, 'id_voucher', 'id_voucher');
    }

    public function payment_payment_records()
    {
        return $this->hasOne(PaymentRecord::class, 'id_service', 'id_service');
    }
    
    public function payment_payment_records_dos()
    {
        return $this->hasOne(PaymentRecord::class, 'id_voucher', 'id_voucher');
    }

}
