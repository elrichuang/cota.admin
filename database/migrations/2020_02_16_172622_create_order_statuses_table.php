<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('member_id')->nullable()->index('idx_member_id')->comment('会员 ID');
            $table->bigInteger('user_id')->nullable()->index('idx_user_id')->comment('管理员 ID');
            $table->bigInteger('order_id')->index('idx_order_id')->comment('订单 ID');
            $table->bigInteger('cash_paid_status')->index('idx_cash_paid_status')->comment('现金支付状态');
            $table->bigInteger('score_paid_status')->index('idx_score_paid_status')->comment('积分支付状态');
            $table->bigInteger('status')->index('idx_status')->comment('订单状态');
            $table->string('memo')->comment('备注');
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
        Schema::dropIfExists('order_statuses');
    }
}
