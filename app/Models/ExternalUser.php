<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalUser extends Model
{
    protected $fillable = ['name', 'email'];

    public function userRatings()
    {
        return $this->hasMany(UserRating::class);
    }
}
