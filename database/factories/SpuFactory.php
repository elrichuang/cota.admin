<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Spu::class, function (Faker $faker) {
    return [
        'user_id'=>1,
        'store_id'=>$faker->numberBetween(1,30),
        'category_id'=>$faker->numberBetween(1,30),
        'brand_id'=>$faker->numberBetween(1,20),
        'name'=>$faker->name,
        'spu_no'=>$faker->uuid,
        'thumb'=>config('app.default_avatar'),
        'tax_classification_code'=>$faker->creditCardNumber,
        'tax_rate_value'=>$faker->numberBetween(1,5)/100,
        'num_sort'=>500,
        'created_at'=>$faker->dateTime
    ];
});
