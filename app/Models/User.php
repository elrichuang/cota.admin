<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable,SoftDeletes;

    const STATUS_ACTIVATED = 'activated';
    const STATUS_DEACTIVATED = 'deactivated';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','super_admin','status','avatar','introduction'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::Class, 'user_roles','user_id','role_id');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\UserLog');
    }

    public function setRoles($rolesIds)
    {
        if ( ! is_array($rolesIds)) {
            $rolesIds = compact('rolesIds');
        }
        $this->roles()->sync($rolesIds, true);
    }

    public function hasRole($roleId)
    {
        return $this->roles->contains($roleId);
    }
}
