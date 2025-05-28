<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    use HasFactory;

    protected $table = 'cost_basic_services'; // Asegúrate de que este es el nombre de la tabla

    protected $fillable = [
        'branch_office_id',
        'name',
        'value',
        'date',
        'note',
    ];

    
    public function branchOffice()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id', 'id_branch');
    }
}