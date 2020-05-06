<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\SliceItem::class, function (Faker $faker) {
    return [
        'slice_id'=>$faker->numberBetween(1,30),
        'image'=>config('app.default_avatar'),
        'url'=>$faker->url,
        'num_sort'=>500,
        'created_at'=>$faker->dateTime
    ];
});
