<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'type_owner',
        'name',
        'number_phone',
    ];
    protected $primaryKey = 'id_owner';

    public function owner_belongs()
    {
        return $this->hasMany(Belong::class, 'id_owner', 'id_owner');
    }
}
