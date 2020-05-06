<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\MerchantOrder::class, function (Faker $faker) {
    return [
        'merchant_id'=>$faker->numberBetween(1,30),
        'store_id'=>$faker->numberBetween(1,100),
        'order_id'=>$faker->numberBetween(1,500),
        'no'=>'M'.date('YmdHis').mt_rand(1000,9999),
        'total_fee'=>$faker->numberBetween(100,500),
        'total_fee_score'=>$faker->numberBetween(1000,5000),
        'total_discount'=>$faker->numberBetween(100,500),
        'total_discount_score'=>$faker->numberBetween(100,500),
        'total_amount'=>$faker->numberBetween(100,500),
        'total_amount_score'=>$faker->numberBetween(1000,5000),
        'commission'=>$faker->numberBetween(10,20),
        'pay_type'=>$faker->randomElement(['weixin','alipay']),
        'invoice_url'=>$faker->url,
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
