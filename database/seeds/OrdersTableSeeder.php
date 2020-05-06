<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\Order::class,500)->make();
        \App\Models\Order::insert($entities->toArray());
    }
}
