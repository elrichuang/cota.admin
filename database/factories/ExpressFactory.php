<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Express::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'alias'=>$faker->name,
        'created_at'=>$faker->dateTime
    ];
});
