<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contains extends Model
{
    use HasFactory;

    public function contains_rule()
    {
        return $this->belongsTo(Rule::class, 'id_rule');
    }

    public function contains_contract()
    {
        return $this->belongsTo(Contract::class, 'id_contract');
    }
}
