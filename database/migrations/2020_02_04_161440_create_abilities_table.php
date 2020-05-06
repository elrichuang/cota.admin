<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->index('idx_user_id')->comment('创建的管理员 ID');
            $table->bigInteger('parent_id')->default(0)->index('idx_parent_id')->comment('父级 ID');
            $table->string('name')->comment('名称');
            $table->string('alias')->index('idx_alias')->comment('代号');
            $table->string('remark')->nullable()->comment('备注');
            $table->string('status')->nullable(false)->default('activated')->comment('状态');
            $table->string('type')->nullable()->comment('类型');
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
        Schema::dropIfExists('abilities');
    }
}
