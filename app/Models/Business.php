<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name_business',
        'logo',
        'electronic_transfer'
    ];

    protected $primaryKey = 'id_business';

    public function business_branch_office()
    {
        return $this->hasMany(BranchOffice::class, 'id_business', 'id_business');
    }
}
