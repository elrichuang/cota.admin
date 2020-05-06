<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSliceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('slice_id')->nullable()->index('idx_slice_id')->comment('幻灯片 ID');
            $table->string('image')->comment('图片路径');
            $table->string('url')->comment('链接');
            $table->integer('num_sort')->comment('排序');
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
        Schema::dropIfExists('slice_items');
    }
}
