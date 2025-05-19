<?php

// app/Models/Reservation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'rut',
        'car_id',
        'branch_office_id',
        'start_date',
        'end_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(RentalCar::class, 'car_id', 'id');
    }

    public function branchOffice()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id','id_branch');
    }

    public function rentRegister()
    {
        return $this->hasOne(RentRegister::class, 'reservation_id','id_rent_register');
    }
}
