<?php

use Illuminate\Database\Seeder;

class MerchantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\Merchant::class,30)->make();
        \App\Models\Merchant::insert($entities->makeVisible(['password'])->toArray());
    }
}
