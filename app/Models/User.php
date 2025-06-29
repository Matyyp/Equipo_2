<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public function user_allow()
    {
        return $this->hasMany(Allow::class, 'id_user');
    }

    public function user_request()
    {
        return $this->hasMany(Request::class, 'id_user');
    }

    public function user_select()
    {
        return $this->hasMany(Select::class, 'id_user');
    }

    public function user_evaluation()
    {
        return $this->hasOne(Evaluation::class, 'id_user');
    }

    public function user_review()
    {
        return $this->hasMany(Review::class, 'id_user');
    }
    
    public function branch_office()
    {
        return $this->belongsTo(BranchOffice::class, 'id_branch_office');
    }
    public function userRatings()
    {
        return $this->hasMany(UserRating::class);
    }

    public function averageRating()
    {
        return $this->hasMany(\App\Models\UserRating::class, 'user_id')
                    ->avg('stars');
    }

    public function averageRatingFormatted()
    {
        $avg = $this->averageRating();
        return $avg !== null ? number_format($avg, 1) : null;
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'user_name',
        'email',
        'password',
        'cell_phone',
        'url_photo_user',
        'user_type',
        'id_branch_office',
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
