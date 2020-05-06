<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Store::class, function (Faker $faker) {
    return [
        'user_id'=>1,
        'merchant_id'=>$faker->numberBetween(1,30),
        'nickname'=>$faker->userName,
        'name'=>$faker->name,
        'weixin_qrcode_image'=>$faker->imageUrl(),
        'weixin_share_content'=>$faker->sentence,
        'thumb'=>config('app.default_avatar'),
        'created_at'=>$faker->dateTime
    ];
});
