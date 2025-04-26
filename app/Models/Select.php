<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Select extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_type_payment',
        'id_user'
    ];

    public function select_user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
    public function select_type_of_payment()
    {
        return $this->belongsTo(TypeOfPayment::class, 'id_payment', 'id_payment');
    }
}
