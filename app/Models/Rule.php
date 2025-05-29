<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Rule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type_contract'
    ];

    protected $primaryKey = 'id_rule'; 

    public function rule_contains()
    {
        return $this->hasMany(Contain::class, 'id_rule', 'id_rule');
    }
}
