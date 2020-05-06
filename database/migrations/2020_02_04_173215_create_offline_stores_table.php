<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfflineStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no')->nullable()->index('idx_no')->comment('代号');
            $table->string('name')->comment('门店名');
            $table->string('phone')->nullable()->comment('电话');
            $table->string('province')->nullable()->comment('省');
            $table->string('city')->nullable()->comment('市');
            $table->string('address')->nullable()->comment('详细地址');
            $table->double('lng')->nullable()->comment('经度');
            $table->double('lat')->nullable()->comment('纬度');
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
        Schema::dropIfExists('offline_stores');
    }
}
