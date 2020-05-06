<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ability;
use Faker\Generator as Faker;

$factory->define(Ability::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'alias' => $faker->userName,
        'user_id' => 1,
        'parent_id' => $faker->numberBetween(0,10),
        'remark' => $faker->sentence,
        'status' => $faker->randomElement(['activated','deactivated']),
        'type' => $faker->randomElement(['view','api']),
        'icon' => 'fa-list',
        'num_sort' => $faker->numberBetween(100,500),
        'show_on_menu' => true,
        'use_url'=>false,
        'url'=>$faker->url,
        'created_at'=>$faker->dateTime,
    ];
});
