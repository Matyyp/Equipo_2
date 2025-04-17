<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Busines extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_business',
        'logo',
        'electronic_transfer'
    ];

    public function business_branch_office()
    {
        return $this->hasMany(BranchOffice::class, 'id_business', 'id_business');
    }
}
