<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_bank',
    ];

    protected $primaryKey = 'id_bank';

    public function bank_bank_detail()
    {
        return $this->hasMany(BankDetail::class, 'id_bank', 'id_bank');
    }


}
