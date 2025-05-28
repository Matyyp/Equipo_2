<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLandingImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_card_id',
        'path'
    ];

    public function serviceLanding()
    {
        return $this->belongsTo(ServiceLanding::class, 'service_card_id');
    }
}