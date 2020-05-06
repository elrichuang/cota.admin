<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->index('idx_user_id')->comment('管理员 ID');
            $table->string('nickname')->index('idx_nickname')->comment('昵称');
            $table->string('phone')->index('idx_phone')->comment('手机号');
            $table->string('password')->comment('密码');
            $table->string('organization_name')->nullable()->comment('公司名');
            $table->string('logo')->nullable()->comment('公司logo');
            $table->string('contact_man')->nullable()->comment('联系人');
            $table->string('contact_email')->nullable()->comment('联系人 Email');
            $table->string('contact_tel')->nullable()->comment('联系电话');
            $table->string('contact_address')->nullable()->comment('联系地址');
            $table->string('weixin_pay_sub_mch_id')->nullable()->index('idx_weixin_pay_sub_mch_id')->comment('微信支付子账号 ID');
            $table->string('alipay_appid')->nullable()->comment('支付宝 AppId');
            $table->string('alipay_app_secret')->nullable()->comment('支付宝 AppSecret');
            $table->string('tg_account')->nullable()->comment('通莞金服账号');
            $table->string('tg_key')->nullable()->comment('通莞金服 key');
            $table->boolean('for_test')->default(false)->comment('测试用账号');
            $table->boolean('has_invoice')->default(false)->comment('是否可以开发票');
            $table->text('memo')->nullable()->comment('备注');
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
        Schema::dropIfExists('merchants');
    }
}
