<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->index('idx_user_id')->comment('管理员 ID');
            $table->bigInteger('merchant_id')->nullable()->index('idx_merchant_id')->comment('商家 ID');
            $table->bigInteger('store_id')->nullable()->index('idx_store_id')->comment('门店 ID');
            $table->string('title')->comment('品牌名');
            $table->string('logo')->nullable()->comment('品牌 Logo');
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
        Schema::dropIfExists('brands');
    }
}
