<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title_opinion',
        'body_opinion',
        'author_opinion',
        'grading',
        'date_opinion',
        'id_user'
    ];

    public function review_user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
