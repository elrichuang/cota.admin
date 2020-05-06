<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpuCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spu_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('store_id')->default(0)->index('idx_store_id')->comment('店铺 ID');
            $table->bigInteger('parent_id')->default(0)->index('idx_parent_id')->comment('父级 ID');
            $table->string('title')->comment('名称');
            $table->string('thumb')->nullable()->comment('缩略图');
            $table->integer('num_sort')->default(500)->comment('排序');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spu_categories');
    }
}
