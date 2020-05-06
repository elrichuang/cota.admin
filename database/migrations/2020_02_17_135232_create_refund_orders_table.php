<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->nullable()->index('idx_user_id')->comment('管理员 ID');
            $table->bigInteger('member_id')->default(0)->nullable()->index('idx_member_id')->comment('会员 ID');
            $table->bigInteger('order_id')->default(0)->nullable()->index('idx_order_id')->comment('订单 ID');
            $table->bigInteger('order_sku_id')->default(0)->nullable()->index('idx_order_sku_id')->comment('订单商品SKU ID');
            $table->string('no')->index('idx_no')->comment('退款单号');
            $table->integer('quantity')->default(0)->index('idx_quantity')->comment('数量');
            $table->string('status')->index('idx_status')->comment('状态');
            $table->string('pay_type')->index('idx_pay_type')->comment('支付方式');
            $table->boolean('full_refund')->default(false)->index('idx_full_refund')->comment('是否全单退款');
            $table->decimal('total_amount',10,2)->index('idx_total_amount')->comment('退款金额');
            $table->integer('total_amount_score')->default(0)->index('idx_total_amount_score')->comment('退款积分');
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
        Schema::dropIfExists('refund_orders');
    }
}
