<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\SpuCategory::class, function (Faker $faker) {
    return [
        'store_id'=>$faker->numberBetween(1,30),
        'parent_id'=>0,
        'title'=>$faker->sentence,
        'thumb'=>config('app.default_avatar'),
        'num_sort'=>500,
        'created_at'=>$faker->dateTime
    ];
});
