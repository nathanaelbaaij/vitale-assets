<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'company', 'adres', 'house_number', 'postal', 'city', 'password', 'scenario_id', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function avatars()
    {
        return $this->hasMany('App\Avatar', 'user_id', 'id');
    }

    /**
     * returns current users avatar url
     * @return mixed
     */
    public function getAvatarAttribute()
    {
        $avatar = $this->avatars->where('active', 1)->first();
        return $avatar ? asset('uploads/avatars/' . $avatar->image_url) : '';
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function scenarios()
    {
        return $this->hasMany('App\Scenario');
    }
}
