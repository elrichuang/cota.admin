<?php

use Illuminate\Database\Seeder;

class ArticleCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = factory(\App\Models\ArticleCategory::class,10)->make();
        \App\Models\ArticleCategory::insert($categories->toArray());
    }
}
