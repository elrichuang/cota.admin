<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->index('idx_user_id')->comment('管理员 ID');
            $table->bigInteger('store_id')->default(0)->index('idx_store_id')->comment('门店 ID');
            $table->bigInteger('category_id')->default(0)->index('idx_category_id')->comment('分类 ID');
            $table->bigInteger('brand_id')->default(0)->index('brand_id')->comment('品牌 ID');
            $table->string('name')->comment('商品名称');
            $table->string('spu_no')->nullable()->index('idx_spu_no')->comment('商品编号');
            $table->string('thumb')->nullable()->comment('缩略图，显示在前端商品和搜索列表');
            $table->string('tax_classification_code')->nullable()->comment('国税分类代码');
            $table->string('tax_rate_value')->nullable()->comment('税率');
            $table->integer('num_sort')->default(500)->index('idx_num_sort')->comment('排序');
            $table->timestamp('on_top_at')->nullable()->comment('置顶时间');
            $table->timestamp('recommend_at')->nullable()->comment('推荐时间');
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
        Schema::dropIfExists('spus');
    }
}
