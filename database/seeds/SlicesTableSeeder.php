<?php

use Illuminate\Database\Seeder;

class SlicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\Slice::class,30)->make();
        \App\Models\Slice::insert($entities->toArray());
    }
}
