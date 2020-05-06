<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->index('idx_user_id')->comment('管理员 ID');
            $table->bigInteger('category_id')->index('idx_category_id')->comment('分类 ID');
            $table->string('title')->comment('标题');
            $table->string('sub_title')->nullable()->comment('副标题');
            $table->string('author')->nullable()->comment('作者');
            $table->text('summary')->nullable()->comment('摘要');
            $table->longText('content')->comment('详细内容');
            $table->string('thumb')->nullable()->comment('缩略图');
            $table->bigInteger('num_like')->comment('点赞数');
            $table->bigInteger('num_view')->comment('浏览数');
            $table->integer('num_sort')->default(500)->comment('排序');
            $table->timestamp('on_top_at')->nullable()->comment('置顶时间');
            $table->timestamp('recommend_at')->nullable()->comment('推荐时间');
            $table->timestamp('published_at')->nullable()->comment('发布时间');
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
        Schema::dropIfExists('articles');
    }
}
