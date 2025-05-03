<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Present extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_contract',
        'id_contact_information'
    ];

    public function presents_contact_information()
    {
        return $this->belongsTo(ContactInformation::class, 'id_contact_information', 'id_contact_information');
    }

    public function presents_contract()
    {
        return $this->belongsTo(Contract::class, 'id_contract', 'id_contract');
    }
}
