<?php

use Illuminate\Database\Seeder;

class MerchantOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\MerchantOrder::class,1000)->make();
        \App\Models\MerchantOrder::insert($entities->toArray());
    }
}
