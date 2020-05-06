<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Sku::class, function (Faker $faker) {
    return [
        'spu_id'=>$faker->numberBetween(1,100),
        'name'=>$faker->name,
        'sku_no'=>$faker->name,
        'images'=>config('app.default_avatar'),
        'price'=>$faker->numberBetween(10000,1000000),
        'price_group_buy'=>$faker->numberBetween(10000,1000000),
        'score'=>$faker->numberBetween(100,2000),
        'score_group_buy'=>$faker->numberBetween(100,2000),
        'less_price'=>$faker->numberBetween(0,20000),
        'less_score'=>$faker->numberBetween(0,2000),
        'less_price_group_buy'=>$faker->numberBetween(0,20000),
        'less_score_group_buy'=>$faker->numberBetween(0,2000),
        'cost'=>$faker->numberBetween(1000,5000),
        'commission'=>$faker->numberBetween(100,2000),
        'commission_group_buy'=>$faker->numberBetween(100,2000),
        'num_max_group_buy_order'=>1,
        'num_max_group_buy_quantity'=>1,
        'norms'=>$faker->word,
        'color'=>$faker->colorName,
        'material'=>$faker->word,
        'tags'=>$faker->word,
        'content'=>$faker->sentence,
        'num_stock'=>$faker->numberBetween(1000,2000),
        'num_sort'=>$faker->numberBetween(100,600),
        'status'=>$faker->randomElement([
            \App\Models\Sku::STATUS_SHELVES,
            \App\Models\Sku::STATUS_TAKEOFF,
            \App\Models\Sku::STATUS_STOCKOUT,
            \App\Models\Sku::STATUS_SOLDOUT
        ]),
        'created_at'=>$faker->dateTime
    ];
});
