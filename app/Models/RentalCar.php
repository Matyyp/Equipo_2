<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalCar extends Model
{
    use HasFactory;

    protected $table = 'rental_cars';

    protected $fillable = [
        'brand_id','model_car_id','year','is_active','branch_office_id',
        'passenger_capacity','transmission','luggage_capacity','price_per_day',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id_brand');
    }

    public function model()
    {
        return $this->belongsTo(ModelCar::class, 'model_car_id', 'id_model');
    }

    public function images()
    {
        return $this->hasMany(RentalCarImage::class, 'rental_car_id', 'id');
    }
    public function branchOffice()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id', 'id_branch');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'car_id', 'id');
    }
}
