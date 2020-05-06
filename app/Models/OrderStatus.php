<?php

namespace App\Models;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;

class OrderStatus extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'user_id',
        'order_id',
        'cash_paid_status',
        'score_paid_status',
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

    public static function addStatus(Order $order, $memo, $memberId = null, $userId = null) {
        $entity = new OrderStatus();
        $entity->member_id = $memberId;
        $entity->user_id = $userId;
        $entity->order_id = $order->id;
        $entity->cash_paid_status = $order->cash_paid_status;
        $entity->score_paid_status = $order->score_paid_status;
        $entity->status = $order->status;
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
