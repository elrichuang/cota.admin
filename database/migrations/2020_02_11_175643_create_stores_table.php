<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->comment('管理员 ID');
            $table->bigInteger('merchant_id')->comment('商家 ID');
            $table->string('nickname')->comment('昵称');
            $table->string('name')->comment('名称');
            $table->string('weixin_qrcode_image')->nullable()->comment('微信公众号二维码');
            $table->string('weixin_share_content')->nullable()->comment('微信分享内容');
            $table->string('thumb')->nullable()->comment('缩略图');
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
        Schema::dropIfExists('stores');
    }
}
