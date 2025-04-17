<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_opinion',
        'body_opinion',
        'author_opinion',
        'grading',
        'date_opinion',
    ];

    public function review_user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
