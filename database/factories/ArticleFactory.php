<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Article::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'category_id' => $faker->numberBetween(1,10),
        'title' => $faker->sentence,
        'sub_title'=>$faker->sentence,
        'author'=>$faker->name,
        'summary'=>$faker->sentence,
        'content'=>$faker->sentence,
        'thumb'=>config('app.default_avatar'),
        'num_like'=>$faker->randomNumber(),
        'num_view'=>$faker->randomNumber(),
        'num_sort'=>$faker->numberBetween(100,1000),
        'published_at'=>$faker->dateTime,
        'created_at'=>$faker->dateTime
    ];
});
