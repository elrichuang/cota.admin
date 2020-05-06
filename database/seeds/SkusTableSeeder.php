<?php

use Illuminate\Database\Seeder;

class SkusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\Sku::class,100)->make();
        \App\Models\Sku::insert($entities->toArray());
    }
}
