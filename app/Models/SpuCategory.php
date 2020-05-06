<?php

namespace App\Models;

class SpuCategory extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'parent_id',
        'store_id',
        'title',
        'thumb',
        'num_sort'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function store()
    {
        return $this->hasOne('App\Models\Store','id','store_id');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\SpuCategory','id','parent_id');
    }

    public function spus()
    {
        return $this->hasMany('App\Models\Spu','category_id','id');
    }
}
