<?php

namespace App\Models;

class OrderSku extends BaseModel
{
    const PAYMENT_CASH = 'cash';
    const PAYMENT_SCORE = 'score';
    const PAYMENT_BOTH = 'both';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'merchant_id',
        'store_id',
        'spu_id',
        'sku_id',
        'payment',
        'original_sku_price',
        'original_sku_score',
        'sku_price',
        'sku_score',
        'group_buy',
        'cost',
        'commission',
        'quantity',
        'refund_quantity',
        'total_freight',
        'total_fee',
        'total_fee_score',
        'total_discount',
        'total_discount_score',
        'total_amount',
        'total_amount_score',
        'total_refund_amount',
        'total_refund_amount_score',
        'memo',
        'status',
        'express_id',
        'express_no'
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

    public function order()
    {
        return $this->hasOne('App\Models\Order','id','order_id');
    }

    public function store()
    {
        return $this->hasOne('App\Models\Store','id','store_id');
    }

    public function spu()
    {
        return $this->hasOne('App\Models\Spu','id','spu_id');
    }

    public function sku()
    {
        return $this->hasOne('App\Models\Sku','id','sku_id');
    }

    public function express()
    {
        return $this->hasOne('App\Models\Express','id','express_id');
    }
}
