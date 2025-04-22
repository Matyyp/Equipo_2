<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    use HasFactory;
    use SoftDeletes;

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
