<?php

use Illuminate\Database\Seeder;

class SpuCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\SpuCategory::class,30)->make();
        \App\Models\SpuCategory::insert($entities->toArray());
    }
}
