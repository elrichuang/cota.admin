<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\ArticleCategory::class, function (Faker $faker) {
    return [
        'parent_id' => 0,
        'user_id' => 1,
        'title' => $faker->sentence,
        'num_sort' => $faker->numberBetween(100,1000),
        'created_at' => $faker->dateTime
    ];
});
