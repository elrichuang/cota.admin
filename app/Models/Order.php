<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class Order extends BaseModel
{
    const TYPE_NORMAL = 'normal'; //一般订单
    const TYPE_GROUP_BUY = 'group_buy'; //团购订单

    const DELIVERY_TYPE_EXPRESS = 'express';
    const DELIVERY_TYPE_TAKE_UP = 'take_up';

    const PAY_TYPE_NONE = null;
    const PAY_TYPE_WEIXIN = 'weixin';
    const PAY_TYPE_ALIPAY = 'alipay';
    //const PAY_TYPE_TGPOSP = 'tgposp';

    const TRADE_TYPE_JSAPI = 'JSAPI';
    const TRADE_TYPE_APP = 'APP';

    const CLIENT_TYPE_H5 = 'h5';
    const CLIENT_TYPE_MINIAPP = 'miniapp';
    const CLIENT_TYPE_APP = 'app';

    const STATUS_UNPAID = 0; //未支付
    const STATUS_PAY_FAILED = 90; //支付失败
    const STATUS_PAID = 100; //已支付
    const STATUS_CONFIRMED = 200; //已确认
    const STATUS_SHIPPED = 300; //已发货
    const STATUS_RECEIVED = 400; //已收货
    const STATUS_CANCELLED = 500; //已取消
    const STATUS_CLOSED = 600; //已关闭
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
        'member_id',
        'no',
        'type',
        'consignee',
        'phone',
        'province',
        'city',
        'address',
        'memo',
        'delivery_type',
        'pay_type',
        'status',
        'cash_paid_status',
        'score_paid_status',
        'total_fee',
        'total_fee_score',
        'total_discount',
        'total_discount_score',
        'total_amount',
        'total_amount_score',
        'ip_address',
        'user_agent',
        'year',
        'month',
        'day',
        'hour',
        'minute',
        'paid_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * 生成订单号
     * @return string
     */
    public static function generateNo() {
        return date('YmdHis').mt_rand(1000,9999);
    }

    /**
     * @param Order $order
     * @param string $payType
     * @param string $tradeNo
     * @throws Exception
     */
    public static function payOk(Order $order,$payType,$tradeNo) {
        try {
            Log::info('支付成功回调',$order->toArray());

            $orderSkus = $order->orderSkus;
            $order->trade_no = $tradeNo;
            $order->pay_type = $payType;
            $order->paid_at = Carbon::now()->toDateTimeString(); // 更新支付时间为当前时间
            $order->cash_paid_status = Order::STATUS_PAID;
            $order->status = Order::STATUS_PAID;
            $order->save();

            OrderStatus::addStatus($order,'支付成功回调');

            foreach ($orderSkus as $orderSku) {
                // 处理order_skus
                $orderSku->status = Order::STATUS_PAID;
                $orderSku->save();

                OrderSkuStatus::addStatus($orderSku, '支付成功回调');
            }
        }catch (Exception $exception) {
            Log::error('支付成功回调出错'.$exception->getMessage(),$order->toArray());
            throw $exception;
        }
    }

    /**
     * @param Order $order
     * @param string $payType
     * @throws Exception
     */
    public static function payFail(Order $order,$payType) {
        try {
            Log::info('支付失败回调',$order->toArray());

            $orderSkus = $order->orderSkus;
            $order->pay_type = $payType;
            $order->cash_paid_status = Order::STATUS_PAY_FAILED;
            $order->status = Order::STATUS_PAY_FAILED;
            $order->save();

            OrderStatus::addStatus($order,'支付失败回调');

            foreach ($orderSkus as $orderSku) {
                // 处理order_skus
                $orderSku->status = Order::STATUS_PAY_FAILED;
                $orderSku->save();

                OrderSkuStatus::addStatus($orderSku, '支付失败回调');
            }
        }catch (Exception $exception) {
            Log::error('支付失败回调出错'.$exception->getMessage(),$order->toArray());
            throw $exception;
        }
    }

    public function member()
    {
        return $this->hasOne('App\Models\Member','id','member_id');
    }

    public function orderSkus()
    {
        return $this->hasMany('App\Models\OrderSku');
    }
}
