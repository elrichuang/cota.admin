<?php

namespace App\Models;


class Spu extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id','category_id','brand_id','name','spu_no','thumb','num_sort','on_top','recommend'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    public function store()
    {
        return $this->hasOne('App\Models\Store','id','store_id');
    }

    public function category()
    {
        return $this->hasOne('App\Models\SpuCategory','id','category_id');
    }

    public function brand()
    {
        return $this->hasOne('App\Models\Brand','id','brand_id');
    }

    public function skus()
    {
        return $this->hasMany('App\Models\Sku','spu_id','id');
    }
}
