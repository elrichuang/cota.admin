<?php

use Illuminate\Database\Seeder;

class SliceItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entities = factory(\App\Models\SliceItem::class,100)->make();
        \App\Models\SliceItem::insert($entities->toArray());
    }
}
