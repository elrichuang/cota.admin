<?php

namespace App\Models;


class Sku extends BaseModel
{
    const STATUS_SHELVES = 'shelves'; //上架
    const STATUS_TAKEOFF = 'takeoff'; //下架
    const STATUS_STOCKOUT = 'stockout'; //缺货
    const STATUS_SOLDOUT = 'soldout'; //售罄
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'spu_id',
        'name',
        'sku_no',
        'price',
        'score',
        'less_price',
        'less_score',
        'price_group_buy',
        'score_group_buy',
        'less_price_group_buy',
        'less_score_group_buy',
        'cost',
        'commission',
        'commission_group_buy',
        'num_max_group_buy_order',
        'num_max_group_buy_quantity',
        'norms',
        'color',
        'material',
        'tags',
        'content',
        'num_stock',
        'num_sort',
        'status'
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
}
