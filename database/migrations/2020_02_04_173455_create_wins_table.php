<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('award_id')->index('idx_award_id')->comment('奖品 ID');
            $table->bigInteger('member_id')->index('idx_member_id')->comment('会员 ID');
            $table->bigInteger('offline_store_id')->default(0)->index('idx_offline_store_id')->comment('线下门店 ID');
            $table->string('name')->nullable()->comment('姓名');
            $table->string('phone')->nullable()->comment('电话');
            $table->string('address')->nullable()->comment('地址');
            $table->string('idcard_no')->nullable()->comment('身份证号');
            $table->string('ip_address')->nullable()->index('idx_ip_address');
            $table->text('user_agent')->nullable();
            $table->string('country')->nullable()->index('idx_country');
            $table->string('province')->nullable()->index('idx_province');
            $table->string('city')->nullable()->index('idx_city');
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
        Schema::dropIfExists('wins');
    }
}
