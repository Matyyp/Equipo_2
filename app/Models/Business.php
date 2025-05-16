<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Business extends Model
{
    use HasFactory;


    protected $fillable = [
        'name_business',
        'logo',
    ];

    protected $primaryKey = 'id_business';

    public function business_branch_office()
    {
        return $this->hasMany(BranchOffice::class, 'id_business', 'id_business');
    }

    public function business_bank()
    {
        return $this->hasMany(BankDetail::class, 'id_business', 'id_business');
    }
}
