<?php

namespace App\Models;


class MemberAddress extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'consignee',
        'phone',
        'province',
        'city',
        'address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function member()
    {
        return $this->hasOne('App\Models\Member','id','member_id');
    }
}
