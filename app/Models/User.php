<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function user_can()
    {
        return $this->hasMany(Can::class, 'id');
    }

    public function user_request()
    {
        return $this->hasMany(Request::class, 'id');
    }

    public function user_select()
    {
        return $this->hasMany(Request::class, 'id');
    }

    public function user_obtain()
    {
        return $this->hasOne(Request::class, 'id');
    }

    public function user_review()
    {
        return $this->hasMany(Review::class, 'id');
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
