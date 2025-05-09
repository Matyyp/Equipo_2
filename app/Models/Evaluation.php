<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Evaluation extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_evaluation',
        'body_evaluation',
        'grading_evaluation',
        'id_user'
    ];

    public function evaluation_user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
}
