<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->index('idx_user_id')->comment('管理员 ID');
            $table->string('name')->comment('名字');
            $table->string('alias')->index('idx_alias')->comment('代号');
            $table->string('thumb')->comment('缩略图');
            $table->decimal('price')->comment('价格');
            $table->integer('store')->comment('库存');
            $table->integer('win_probability')->comment('中奖概率');
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
        Schema::dropIfExists('awards');
    }
}
