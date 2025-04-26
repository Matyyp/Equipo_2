<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'region',
        'commune'
    ];

    public function location_branch_office()
    {
        return $this->hasMany(BranchOffice::class, 'id_location', 'id_location');
    }
}
