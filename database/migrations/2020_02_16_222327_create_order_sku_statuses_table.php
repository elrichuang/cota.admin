<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSkuStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_sku_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->nullable()->index('idx_user_id')->comment('管理员 ID');
            $table->bigInteger('member_id')->default(0)->nullable()->index('idx_member_id')->comment('会员 ID');
            $table->bigInteger('merchant_id')->default(0)->nullable()->index('idx_merchant_id')->comment('商家 ID');
            $table->bigInteger('order_sku_id')->default(0)->nullable()->index('idx_order_sku_id')->comment('订单商品 SKU ID');
            $table->integer('status')->index('idx_status')->comment('状态');
            $table->string('memo')->nullable()->comment('备注');
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
        Schema::dropIfExists('order_sku_statuses');
    }
}
