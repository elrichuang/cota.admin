<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Brand::class, function (Faker $faker) {
    return [
        'user_id'=>1,
        'merchant_id'=>$faker->numberBetween(1,30),
        'store_id'=>$faker->numberBetween(1,100),
        'title'=>$faker->sentence,
        'logo'=>config('app.default_avatar'),
        'created_at'=>$faker->dateTime
    ];
});
