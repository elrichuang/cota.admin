<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Cart::class, function (Faker $faker) {
    return [
        'member_id'=>$faker->numberBetween(1,100),
        'merchant_id'=>$faker->numberBetween(1,20),
        'store_id'=>$faker->numberBetween(1,20),
        'spu_id'=>$faker->numberBetween(1,30),
        'sku_id'=>$faker->numberBetween(1,100),
        'payment'=>$faker->randomElement([
            'cash',
            'score',
            'both'
        ]),
        'quantity'=>$faker->numberBetween(1,20),
        'status'=>$faker->randomElement([
            'added',
            'ordered'
        ]),
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
