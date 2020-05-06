<?php

use Illuminate\Database\Seeder;

class OrderSkuStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\OrderSkuStatus::class,1000)->make();
        \App\Models\OrderSkuStatus::insert($entities->toArray());
    }
}
