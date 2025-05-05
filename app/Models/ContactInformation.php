<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactInformation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'data_contact',
        'type_contact',
        'id_branch_office'
    ];

    public function contact_information_presents()
    {
        return $this->hasMany(Present::class, 'id_contact_information', 'id_contact_information');
    }
}
