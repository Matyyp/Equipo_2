<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presents extends Model
{
    use HasFactory;

    public function presents_contract_information()
    {
        return $this->belongsTo(Contract_information::class, 'id_contract_information');
    }

    public function presents_contract()
    {
        return $this->belongsTo(Contract::class, 'id_contract');
    }
}
