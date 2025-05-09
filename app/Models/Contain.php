<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Contain extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_rule',
        'id_contract',
    ];

    public function contains_rule()
    {
        return $this->belongsTo(Rule::class, 'id_rule', 'id_rule');
    }

    public function contains_contract()
    {
        return $this->belongsTo(Contract::class, 'id_contract', 'id_contract');
    }
}
