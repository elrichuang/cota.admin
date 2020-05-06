<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false)->comment('姓名');
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('email')->nullable(false)->unique()->comment('邮箱');
            $table->string('password')->nullable(false)->comment('密码');
            $table->boolean('super_admin')->default(false)->comment('是否超级管理员');
            $table->string('status')->default('activated')->comment('状态');
            $table->text('introduction')->nullable()->comment('说明');
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
        Schema::dropIfExists('users');
    }
}
