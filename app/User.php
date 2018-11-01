<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\MailResetPasswordToken;

class User extends Authenticatable
{
    protected $guard_name = "web";
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','picture'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }
    
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'user_company');
    }
}
