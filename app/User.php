<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'profile_image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all of the details that belong to the user.
     */
    public function details()
    {
        return $this->hasMany(\App\UserDetails::class)->select('user_id', 'key', 'value');
    }

    /**
     * Get all of the post that belong to the user.
     */
    public function posts()
    {
        return $this->hasMany(\App\Posts::class);
    }

    /**
     * pluck details.
     */
    public function getDetailsAttribute()
    {
        return $this->details()->pluck('value', 'key')->all();
    }
}
