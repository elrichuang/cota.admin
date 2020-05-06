<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'name' => $faker->name,
        'created_at'=>$faker->dateTime,
    ];
});
