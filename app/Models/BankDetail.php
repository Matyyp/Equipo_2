<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rut',
        'id_bank',
        'id_type_account',
        'account_number'
    ];

    protected $primaryKey = 'id_bank_details';

    public function bank_detail_bank()
    {
        return $this->belongsTo(Bank::class, 'id_bank', 'id_bank');
    }

    public function bank_detail_type_account()
    {
        return $this->belongsTo(TypeAccount::class, 'id_type_account', 'id_type_account');
    }
}
