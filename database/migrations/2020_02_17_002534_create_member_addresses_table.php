<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('member_id')->index('idx_member_id')->comment('会员 ID');
            $table->string('consignee')->comment('收件人');
            $table->string('phone')->comment('手机');
            $table->string('province')->comment('省');
            $table->string('city')->comment('市');
            $table->string('address')->comment('详细地址');
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
        Schema::dropIfExists('member_addresses');
    }
}
