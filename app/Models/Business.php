<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    public function business_branch_office()
    {
        return $this->hasMany(Branch_office::class, 'id_business');
    }
}
