<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    public function users()
    {
        return $this->belongsToMany(User::Class, 'user_roles','role_id','user_id');
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::Class, 'role_abilities','role_id','ability_id');
    }

    public function setAbilities($abilitiesIds)
    {
        if ( ! is_array($abilitiesIds)) {
            $abilitiesIds = compact('$abilitiesIds');
        }
        $this->abilities()->sync($abilitiesIds, true);
    }

    public function hasAbility($abilityId)
    {
        return $this->abilities->contains($abilityId);
    }
}
