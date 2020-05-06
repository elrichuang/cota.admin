<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;

class OrderSkuStatus extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'merchant_id',
        'user_id',
        'order_sku_id',
        'status',
        'memo',
        'ip_address',
        'user_agent',
        'year',
        'month',
        'day',
        'hour',
        'minute',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    public static function addStatus(OrderSku $orderSku, $memo, $memberId = null, $userId = null) {
        $entity = new OrderSkuStatus();
        $entity->member_id = $memberId;
        $entity->merchant_id = $orderSku->merchant->id;
        $entity->user_id = $userId;
        $entity->order_sku_id = $orderSku->id;
        $entity->status = $orderSku->status;
        $entity->memo = $memo;
        $entity->ip_address = Request::ip();
        $entity->user_agent = Request::userAgent();
        $entity->year = Carbon::now()->year;
        $entity->month = Carbon::now()->month;
        $entity->day = Carbon::now()->day;
        $entity->hour = Carbon::now()->hour;
        $entity->minute = Carbon::now()->minute;
        $entity->save();
    }
}
