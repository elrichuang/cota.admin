<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;

class RefundOrderStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'user_id',
        'refund_order_id',
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

    public static function addStatus(RefundOrder $refundOrder, $memo, $memberId = null, $userId = null) {
        $entity = new RefundOrderStatus();
        $entity->member_id = $memberId;
        $entity->user_id = $userId;
        $entity->refund_order_id = $refundOrder->id;
        $entity->status = $refundOrder->status;
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
