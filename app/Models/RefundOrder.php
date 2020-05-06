<?php

namespace App\Models;


class RefundOrder extends BaseModel
{
    const STATUS_REFUNDING = 700; //申请退款
    const STATUS_REFUND_REJECTED = 800; //退款申请驳回
    const STATUS_REFUND_FAILED = 900; //退款失败
    const STATUS_REFUNDED = 1000; //已退款

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'member_id',
        'order_id',
        'order_sku_id',
        'no',
        'quantity',
        'status',
        'pay_type',
        'full_refund',
        'total_amount',
        'total_amount_score',
        'memo',
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

    ];

    /**
     * 生成退款单号
     * @return string
     */
    public static function generateNo() {
        return 'TK'.date('YmdHis').mt_rand(1000,9999);
    }

    public function member()
    {
        return $this->hasOne('App\Models\Member','id','member_id');
    }

    public function order()
    {
        return $this->hasOne('App\Models\Order','id','order_id');
    }

    public function orderSku()
    {
        return $this->hasOne('App\Models\OrderSku','id','order_sku_id');
    }
}
