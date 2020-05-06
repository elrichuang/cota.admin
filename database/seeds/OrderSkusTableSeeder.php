<?php

use Illuminate\Database\Seeder;

class OrderSkusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\OrderSku::class,1000)->make();
        \App\Models\OrderSku::insert($entities->toArray());
    }
}
