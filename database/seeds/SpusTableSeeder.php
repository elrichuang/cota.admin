<?php

use Illuminate\Database\Seeder;

class SpusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\Spu::class,100)->make();
        \App\Models\Spu::insert($entities->toArray());
    }
}
