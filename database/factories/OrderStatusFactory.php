<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\OrderStatus::class, function (Faker $faker) {
    return [
        'member_id'=>$faker->numberBetween(1,100),
        'user_id'=>$faker->numberBetween(0,10),
        'order_id'=>$faker->numberBetween(1,500),
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
