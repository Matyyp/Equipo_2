<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type_contract'
    ];

    public function rule_contains()
    {
        return $this->hasMany(Contain::class, 'id_rule', 'id_rule');
    }
}
