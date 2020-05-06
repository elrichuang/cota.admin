<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname')->nullable(false)->unique()->comment('昵称');
            $table->string('email')->nullable()->unique()->comment('邮箱');
            $table->string('name')->nullable()->comment('姓名');
            $table->string('phone')->nullable()->unique()->comment('手机');
            $table->string('weixin_appid')->nullable()->index('idx_weixin_appid')->comment('微信 appid');
            $table->string('weixin_openid')->nullable()->unique()->comment('微信 openid');
            $table->string('weixin_unionid')->nullable()->unique()->comment('微信 unionid');
            $table->string('password')->nullable()->comment('密码');
            $table->string('avatar')->nullable()->comment('头像');
            $table->tinyInteger('sex')->nullable(false)->default(0)->index('idx_sex')->comment('性别');
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
        Schema::dropIfExists('members');
    }
}
