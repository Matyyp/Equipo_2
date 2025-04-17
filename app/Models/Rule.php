<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    public function rule_contains()
    {
        return $this->hasMany(Contains::class, 'id_rule');
    }
}
