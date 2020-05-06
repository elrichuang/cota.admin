<?php

use Illuminate\Database\Seeder;

class OrderStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\OrderStatus::class,1000)->make();
        \App\Models\OrderStatus::insert($entities->toArray());
    }
}
