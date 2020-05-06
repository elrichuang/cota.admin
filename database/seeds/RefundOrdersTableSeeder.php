<?php

use Illuminate\Database\Seeder;

class RefundOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\RefundOrder::class,500)->make();
        \App\Models\RefundOrder::insert($entities->toArray());
    }
}
