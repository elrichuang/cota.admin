<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Member;
use Faker\Generator as Faker;

$factory->define(Member::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'nickname' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'weixin_appid' => config('services.weixin.client_id'),
        'weixin_openid' => $faker->sentence,
        'weixin_unionid' => $faker->sentence,
        'avatar' => config('app.default_avatar'),
        'sex' => $faker->randomElement([0,1,2]),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'ip_address' => $faker->ipv4,
        'user_agent' => $faker->userAgent,
        'country' => '中国',
        'province' => $faker->randomElement(['北京','上海','广东','浙江']),
        'city' => $faker->randomElement(['北京','上海','广州','深圳','杭州']),
        'year' => $faker->year,
        'month' => $faker->month,
        'day' => $faker->dayOfMonth,
        'hour' => $faker->numberBetween(0,23),
        'minute' => $faker->numberBetween(0,59),
        'created_at'=>$faker->dateTime,
    ];
});
