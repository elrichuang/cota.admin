<?php

namespace App\Models;


class Cart extends BaseModel
{
    const PAYMENT_CASH = 'cash';
    const PAYMENT_SCORE = 'score';
    const PAYMENT_BOTH = 'both';

    const STATUS_ADDED = 'added'; //添加购物车
    const STATUS_ORDERED = 'ordered'; //已下单

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'sku_id',
        'spu_id',
        'merchant_id',
        'store_id',
        'payment',
        'quantity',
        'status',
        'ip_address',
        'user_agent',
        'year',
        'month',
        'day',
        'hour',
        'minute'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    public function spu()
    {
        return $this->hasOne('App\Models\Spu','id','spu_id');
    }

    public function sku()
    {
        return $this->hasOne('App\Models\Sku','id','sku_id');
    }

    public function store()
    {
        return $this->hasOne('App\Models\Store','id','store_id');
    }

    public function member()
    {
        return $this->hasOne('App\Models\Member','id','member_id');
    }

    public function merchant()
    {
        return $this->hasOne('App\Models\Merchant','id','merchant_id');
    }
}
