<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\OrderSku::class, function (Faker $faker) {
    return [
        'order_id'=>$faker->numberBetween(1,500),
        'merchant_id'=>$faker->numberBetween(1,30),
        'store_id'=>$faker->numberBetween(1,100),
        'spu_id'=>$faker->numberBetween(1,100),
        'sku_id'=>$faker->numberBetween(1,100),
        'payment'=>$faker->randomElement(['cash','score','both']),
        'original_sku_price'=>$faker->numberBetween(100,1000),
        'original_sku_score'=>$faker->numberBetween(1000,2000),
        'sku_price'=>$faker->numberBetween(100,1000),
        'sku_score'=>$faker->numberBetween(1000,2000),
        'group_buy'=>$faker->boolean,
        'cost'=>$faker->numberBetween(100,1000),
        'commission'=>$faker->numberBetween(1,10),
        'quantity'=>$faker->numberBetween(1,10),
        'refund_quantity'=>$faker->numberBetween(1,10),
        'total_fee'=>$faker->numberBetween(100,1000),
        'total_fee_score'=>$faker->numberBetween(100,1000),
        'total_freight'=>$faker->numberBetween(10,100),
        'total_discount'=>$faker->numberBetween(10,100),
        'total_discount_score'=>$faker->numberBetween(100,1000),
        'total_amount'=>$faker->numberBetween(100,1000),
        'total_amount_score'=>$faker->numberBetween(1000,2000),
        'total_refund_amount'=>$faker->numberBetween(100,1000),
        'total_refund_amount_score'=>$faker->numberBetween(1000,2000),
        'memo'=>$faker->sentence,
        'status'=>$faker->randomElement([
            0,
            100,
            200,
            300,
            400,
            500
        ]),
        'express_id'=>$faker->numberBetween(1,5),
        'express_no'=>$faker->bankAccountNumber,
        'created_at'=>$faker->dateTime
    ];
});
