<?php

namespace App\Models;

class Brand extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'merchant_id',
        'store_id',
        'title',
        'logo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    public function merchant()
    {
        return $this->hasOne('App\Models\Merchant','id','merchant_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function store()
    {
        return $this->hasOne('App\Models\Store','id','store_id');
    }

    public function spus()
    {
        return $this->hasMany('App\Models\Spu');
    }
}
