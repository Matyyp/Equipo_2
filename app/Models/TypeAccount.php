<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_type_account',
    ];

    protected $primaryKey = 'id_type_account';

    public function type_account_bank_detail()
    {
        return $this->hasMany(BankDetail::class, 'id_type_account', 'id_type_account');
    }
}
