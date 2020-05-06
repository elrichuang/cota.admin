<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\RefundOrder::class, function (Faker $faker) {
    return [
        'user_id'=>1,
        'member_id'=>$faker->numberBetween(1,100),
        'no'=>$faker->creditCardNumber,
        'order_id'=>$faker->numberBetween(1,500),
        'order_sku_id'=>$faker->numberBetween(1,500),
        'quantity'=>$faker->numberBetween(1,10),
        'status'=>$faker->randomElement([
            700,
            800,
            900,
            1000
        ]),
        'full_refund'=>$faker->boolean,
        'pay_type'=>$faker->randomElement(['weixin','alipay']),
        'total_amount'=>$faker->numberBetween(100,1000),
        'total_amount_score'=>$faker->numberBetween(1000,2000),
        'memo'=>$faker->sentence,
        'ip_address'=>$faker->ipv4,
        'user_agent'=>$faker->userAgent,
        'year'=>$faker->year,
        'month'=>$faker->month,
        'day'=>$faker->dayOfMonth,
        'hour'=>$faker->numberBetween(0,23),
        'minute'=>$faker->numberBetween(0,59),
        'created_at'=>$faker->dateTime
    ];
});
