<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\BranchOfficeScope;


class BranchOffice extends Model
{
    use HasFactory;


    protected $fillable = [
        'schedule',
        'street',
        'id_location',
        'id_business',
        'name_branch_offices'
    ];

    protected $primaryKey = 'id_branch';

    protected $table = 'branch_offices';

    public $branchOfficeColumn = 'id_branch'; // IMPORTANTE: DEBE SER PUBLIC

    protected static function booted()
    {
        static::addGlobalScope(new BranchOfficeScope);
    }

    

    public function branch_office_business()
    {
        return $this->belongsTo(Business::class, 'id_business', 'id_business');
    }

    public function branch_office_location()
    {
        return $this->belongsTo(Location::class, 'id_location', 'id_location');
    }

    public function branch_office_service()
    {
        return $this->hasMany(Service::class, 'id_branch', 'id_branch');
    }

    public function branch_office_contract()
    {
        return $this->hasMany(Contract::class, 'id_branch_office', 'id_branch');
    }
    public function business()
    {
        return $this->belongsTo(Business::class, 'id_business');
    }

}
