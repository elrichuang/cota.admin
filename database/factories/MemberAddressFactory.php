<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\MemberAddress::class, function (Faker $faker) {
    return [
        'member_id'=>$faker->numberBetween(1,100),
        'consignee'=>$faker->name,
        'phone'=>$faker->phoneNumber,
        'province'=>$faker->randomElement([
            '广东',
            '浙江',
            '湖南'
        ]),
        'city'=>$faker->randomElement([
            '广州',
            '深圳',
            '南京',
            '长沙'
        ]),
        'address'=>$faker->sentence,
        'created_at'=>$faker->dateTime
    ];
});
