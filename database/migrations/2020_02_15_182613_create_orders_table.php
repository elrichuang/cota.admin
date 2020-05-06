<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('member_id')->index('idx_member_id')->comment('会员 ID');
            $table->string('no')->index('idx_no')->comment('订单号');
            $table->string('type')->default('normal')->index('idx_type')
                ->comment('订单类型 normal 一般,group_buy 团购');
            $table->string('consignee')->index('idx_consignee')->comment('收件人');
            $table->string('phone')->index('idx_phone')->comment('收件人手机号');
            $table->string('province')->index('idx_province')->comment('省');
            $table->string('city')->index('idx_city')->comment('市');
            $table->string('address')->comment('详细地址');
            $table->string('memo')->nullable()->comment('备注');
            $table->string('delivery_type')->default('express')->index('idx_delivery_type')
                ->comment('配送类型 express 快递,take_up 自提');
            $table->string('pay_type')->nullable()->index('idx_pay_type')
                ->comment('支付类型 weixin 微信支付,alipay 支付宝');
            $table->integer('status')->default(0)->index('idx_status')
                ->comment('订单状态 0 未支付, 100 已支付, 200 已确认, 300 已发货, 400 已收货, 500 已取消 600 已关闭');
            $table->integer('cash_paid_status')->default(0)->index('idx_cash_paid_status')
                ->comment('现金支付状态');
            $table->integer('score_paid_status')->default(0)->index('idx_score_paid_status')
                ->comment('积分支付状态');
            $table->decimal('total_fee',10,2)->default(0)->index('idx_total_fee')
                ->comment('现金总计');
            $table->integer('total_fee_score')->default(0)->index('idx_total_fee_score')
                ->comment('积分总计');
            $table->decimal('total_discount',10,2)->default(0)->index('idx_total_discount')
                ->comment('现金优惠总计');
            $table->integer('total_discount_score')->default(0)->index('idx_total_discount_score')
                ->comment('积分优惠总计');
            $table->decimal('total_amount',10,2)->default(0)->index('idx_total_amount')
                ->comment('现金实付');
            $table->integer('total_amount_score')->default(0)->index('idx_total_amount_score')
                ->comment('积分实付');
            $table->string('ip_address')->nullable()->index('idx_ip_address');
            $table->text('user_agent')->nullable();
            $table->year('year')->nullable()->index('idx_year');
            $table->tinyInteger('month')->nullable()->index('idx_month');
            $table->tinyInteger('day')->nullable()->index('idx_day');
            $table->tinyInteger('hour')->nullable()->index('idx_hour');
            $table->tinyInteger('minute')->nullable()->index('idx_minute');
            $table->timestamp('paid_at')->nullable()->comment('支付时间');
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
        Schema::dropIfExists('orders');
    }
}
