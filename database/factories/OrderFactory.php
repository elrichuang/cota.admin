<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Order::class, function (Faker $faker) {
    return [
        'member_id'=>$faker->numberBetween(1,100),
        'no'=>$faker->date('YmdHis').mt_rand(1000,9999),
        'type'=>$faker->randomElement(['normal','group_buy']),
        'consignee'=>$faker->name,
        'phone'=>$faker->phoneNumber,
        'province'=>$faker->randomElement([
            '广东',
            '北京',
            '上海',
            '浙江',
            '福建',
            '湖南',
        ]),
        'city'=>$faker->randomElement([
            '广州',
            '深圳',
            '佛山',
            '东莞',
            '北京',
            '上海',
            '南京',
            '厦门',
            '长沙'
        ]),
        'address'=>$faker->address,
        'memo'=>$faker->sentence,
        'delivery_type'=>$faker->randomElement(['express','take_up']),
        'pay_type'=>$faker->randomElement(['weixin','alipay']),
        'status'=>$faker->randomElement([
            0,
            100,
            200,
            300,
            400,
            500,
            600
        ]),
        'cash_paid_status'=>$faker->randomElement([0,100,500,600]),
        'score_paid_status'=>$faker->randomElement([0,100,500,600]),
        'total_fee'=>$faker->numberBetween(100,1000),
        'total_fee_score'=>$faker->numberBetween(1000,3000),
        'total_discount'=>$faker->numberBetween(10,100),
        'total_discount_score'=>$faker->numberBetween(100,500),
        'total_amount'=>$faker->numberBetween(100,1000),
        'total_amount_score'=>$faker->numberBetween(100,1000),
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
