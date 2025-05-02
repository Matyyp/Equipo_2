<?php

namespace App\Models;

use App\Models\Service;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentRecord extends Model
{
    use HasFactory, SoftDeletes;

    // Si tu tabla se llama payment_records y la PK es id_payment:
    protected $table      = 'payment_records';
    protected $primaryKey = 'id_payment';
    public $incrementing  = true;
    protected $keyType    = 'int';

    protected $fillable = [
        'id_service',
        'id_voucher',
        'payment_date',
        'type_payment',
        'amount',
    ];

    /**
     * Relación con Service
     */
    public function paymentRecordPayment()
    {
        return $this->belongsTo(
            Payment::class,
            'id_service',   // FK en esta tabla
            'id_service'    // PK en services
        );
    }

    /**
     * Relación con Voucher
     */
    public function PaymentRecordsVoucher()
    {
        return $this->belongsTo(
            Payment::class,
            'id_voucher',   // FK en esta tabla
            'id_voucher'    // PK en vouchers
        );
    }
    public function paymentRecords()
    {
        // FK en payment_records = id_register, PK local = id
        return $this->hasMany(PaymentRecord::class, 'id_register', 'id');
    }
}
