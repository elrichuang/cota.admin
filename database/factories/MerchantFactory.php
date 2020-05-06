<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Merchant::class, function (Faker $faker) {
    return [
        'user_id'=>1,
        'nickname'=>$faker->userName,
        'phone'=>$faker->phoneNumber,
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'organization_name'=>$faker->company,
        'logo'=>config('app.default_avatar'),
        'contact_man'=>$faker->name,
        'contact_email'=>$faker->email,
        'contact_tel'=>$faker->phoneNumber,
        'contact_address'=>$faker->address,
        'weixin_pay_sub_mch_id'=>$faker->uuid,
        'alipay_appid'=>$faker->uuid,
        'alipay_app_secret'=>$faker->uuid,
        'tg_account'=>$faker->creditCardNumber,
        'tg_key'=>$faker->creditCardNumber,
        'for_test'=>$faker->boolean,
        'has_invoice'=>$faker->boolean,
        'memo'=>$faker->sentence,
        'created_at'=>$faker->dateTime
    ];
});
