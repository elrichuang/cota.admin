<?php

use Illuminate\Database\Seeder;

class ExpressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\Express::class,5)->make();
        \App\Models\Express::insert($entities->toArray());
    }
}
