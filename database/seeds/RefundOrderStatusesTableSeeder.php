<?php

use Illuminate\Database\Seeder;

class RefundOrderStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\RefundOrderStatus::class,500)->make();
        \App\Models\RefundOrderStatus::insert($entities->toArray());
    }
}
