<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\OrderSkuStatus::class, function (Faker $faker) {
    return [
        'user_id'=>1,
        'member_id'=>$faker->numberBetween(1,100),
        'merchant_id'=>$faker->numberBetween(1,50),
        'order_sku_id'=>$faker->numberBetween(1,1000),
        'status'=>$faker->randomElement([
            0,
            100,
            200,
            300,
            400,
            500
        ]),
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
