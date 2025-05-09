<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ContactInformation extends Model
{
    use HasFactory;


    protected $fillable = [
        'data_contact',
        'type_contact',
        'id_branch_office'
    ];

    protected $primaryKey = 'id_contact_information';

    public function contact_information_presents()
    {
        return $this->hasMany(Present::class, 'id_contact_information', 'id_contact_information');
    }
}
