<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('merchant_id')->index('idx_merchant_id')->comment('商家 ID');
            $table->bigInteger('store_id')->index('idx_store_id')->comment('店铺 ID');
            $table->bigInteger('order_id')->index('idx_order_id')->comment('订单 ID');
            $table->string('no')->index('idx_no')->comment('商家订单号');
            $table->decimal('total_fee',10,2)->default(0)->comment('现金总计');
            $table->integer('total_fee_score')->default(0)->comment('总积分');
            $table->decimal('total_discount',10,2)->default(0)->comment('现金优惠');
            $table->integer('total_discount_score')->default(0)->comment('积分优惠');
            $table->decimal('total_amount',10,2)->default(0)->comment('现金实付');
            $table->integer('total_amount_score')->default(0)->comment('积分实付');
            $table->decimal('commission',10,2)->default(0)->comment('佣金');
            $table->string('pay_type')->default('weixin')->index('idx_pay_type')->comment('支付方式');
            $table->text('invoice_url')->nullable()->comment('电子发票地址');
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
        Schema::dropIfExists('merchant_orders');
    }
}
