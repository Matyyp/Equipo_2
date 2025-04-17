<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact_information extends Model
{
    use HasFactory;

    public function contact_information_presents()
    {
        return $this->hasMany(Present::class, 'id_contract_information');
    }
}
