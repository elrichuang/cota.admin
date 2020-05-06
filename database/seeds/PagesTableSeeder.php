<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = factory(\App\Models\Page::class,100)->make();
        \App\Models\Page::insert($pages->toArray());
    }
}
