<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('member_id')->default(0)->index('idx_member_id')->comment('会员 ID');
            $table->bigInteger('merchant_id')->default(0)->index('idx_merchant_id')->comment('商家 ID');
            $table->bigInteger('store_id')->default(0)->index('idx_store_id')->comment('店铺 ID');
            $table->bigInteger('spu_id')->default(0)->index('idx_spu_id')->comment('商品 SPU ID');
            $table->bigInteger('sku_id')->default(0)->index('idx_sku_id')->comment('SKU ID');
            $table->string('payment')->default('cash')->index('idx_payment')->comment('支付方式 cash 纯现金,score 纯积分,both 积分+现金');
            $table->integer('quantity')->default(0)->index('idx_quantity')->comment('数量');
            $table->string('status')->default('added')->index('idx_status')->comment('状态 added 加入购物车,ordered 已下单');
            $table->string('ip_address')->nullable()->index('idx_ip_address');
            $table->text('user_agent')->nullable();
            $table->year('year')->nullable()->index('idx_year');
            $table->tinyInteger('month')->nullable()->index('idx_month');
            $table->tinyInteger('day')->nullable()->index('idx_day');
            $table->tinyInteger('hour')->nullable()->index('idx_hour');
            $table->tinyInteger('minute')->nullable()->index('idx_minute');
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
        Schema::dropIfExists('carts');
    }
}
