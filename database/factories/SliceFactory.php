<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Slice::class, function (Faker $faker) {
    return [
        'user_id'=>1,
        'name'=>$faker->userName,
        'created_at'=>$faker->dateTime
    ];
});
