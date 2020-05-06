<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_skus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->index('idx_order_id')->comment('订单 ID');
            $table->bigInteger('merchant_id')->index('idx_merchant_id')->comment('商家 ID');
            $table->bigInteger('store_id')->index('idx_store_id')->comment('店铺 ID');
            $table->bigInteger('spu_id')->index('idx_spu_id')->comment('商品 SPU ID');
            $table->bigInteger('sku_id')->index('idx_sku_id')->comment('商品 SKU ID');
            $table->string('payment')->default('cash')->index('idx_payment')->comment('支付方式，参考购物车');
            $table->decimal('original_sku_price',10,2)->default(0)->index('idx_original_sku_price')->comment('商品原价');
            $table->integer('original_sku_score')->default(0)->index('idx_original_sku_score')->comment('商品积分');
            $table->decimal('sku_price',10,2)->default(0)->index('idx_sku_price')->comment('商品实际购买价格');
            $table->integer('sku_score')->default(0)->index('idx_sku_score')->comment('商品积分');
            $table->boolean('group_buy')->default(false)->index('idx_group_buy')->comment('是否团购');
            $table->decimal('cost',10,2)->default(0)->index('idx_cost')->comment('成本');
            $table->decimal('commission',10,2)->default(0)->index('idx_commission')->comment('佣金');
            $table->integer('quantity')->default(0)->index('idx_quantity')->comment('数量');
            $table->integer('refund_quantity')->default(0)->index('idx_refund_quantity')->comment('退款数量');
            $table->decimal('total_fee',10,2)->default(0)->index('idx_total_fee')->comment('总计');
            $table->integer('total_fee_score')->default(0)->index('idx_total_fee_score')->comment('积分总计');
            $table->decimal('total_freight',10,2)->default(0)->index('idx_total_freight')->comment('运费总计');
            $table->decimal('total_discount',10,2)->default(0)->index('idx_total_discount')->comment('优惠总计');
            $table->integer('total_discount_score')->default(0)->index('idx_total_discount_score')->comment('积分优惠总计');
            $table->decimal('total_amount',10,2)->default(0)->index('idx_total_amount')->comment('现金实付');
            $table->integer('total_amount_score')->default(0)->index('idx_total_amount_score')->comment('积分实付');
            $table->decimal('total_refund_amount',10,2)->default(0)->index('idx_total_refund_amount')->comment('退款现金实付');
            $table->integer('total_refund_amount_score')->default(0)->index('idx_total_refund_amount_score')->comment('退款积分实付');
            $table->string('memo')->nullable()->comment('备注');
            $table->integer('status')->index('idx_status')->comment('状态');
            $table->bigInteger('express_id')->nullable()->index('idx_express_id')->comment('快递公司 ID');
            $table->string('express_no')->nullable()->index('idx_express_no')->comment('快递单号');
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
        Schema::dropIfExists('order_skus');
    }
}
