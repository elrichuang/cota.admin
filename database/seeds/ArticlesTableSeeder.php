<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articles = factory(\App\Models\Article::class,100)->make();
        \App\Models\Article::insert($articles->toArray());
    }
}
