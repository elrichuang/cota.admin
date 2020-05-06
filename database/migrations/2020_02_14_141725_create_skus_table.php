<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('spu_id')->index('idx_spu_id')->comment('SPU ID');
            $table->string('name')->comment('名称');
            $table->string('sku_no')->nullable()->comment('SKU 代号');
            $table->text('images')->nullable()->comment('图片');
            $table->decimal('price',10,2)->default(0)->comment('价格');
            $table->integer('score')->default(0)->comment('积分');
            $table->decimal('less_price',10,2)->default(0)->comment('积分+现金时的现金部分');
            $table->integer('less_score')->default(0)->comment('积分+现金时的积分部分');
            $table->decimal('price_group_buy',10,2)->default(0)->comment('团购价格');
            $table->integer('score_group_buy')->default(0)->comment('团购积分');
            $table->decimal('less_price_group_buy',10,2)->default(0)->comment('团购积分+现金时的现金部分');
            $table->integer('less_score_group_buy')->default(0)->comment('团购积分+现金时的积分部分');
            $table->decimal('cost',10,2)->default(0)->comment('成本');
            $table->decimal('commission',10,2)->default(0)->comment('佣金');
            $table->decimal('commission_group_buy',10,2)->default(0)->comment('团购佣金');
            $table->integer('num_max_group_buy_order')->default(1)->comment('每人最多可团购下单数 0不限');
            $table->integer('num_max_group_buy_quantity')->default(1)->comment('每人最多可团购数量 0不限');
            $table->string('norms')->nullable()->index('idx_norms')->comment('规格');
            $table->string('color')->nullable()->index('idx_color')->comment('颜色');
            $table->string('material')->nullable()->index('idx_material')->comment('材质');
            $table->string('tags')->nullable()->index('idx_tags')->comment('标签');
            $table->longText('content')->nullable()->comment('详细内容');
            $table->integer('num_stock')->default(0)->index('idx_num_stock')->comment('剩余库存');
            $table->integer('num_sort')->default(500)->comment('排序');
            $table->string('status')->default('')->index('idx_status')->comment('状态 shelves上架,takeoff下架,stockout缺货,soldout售罄');
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
        Schema::dropIfExists('skus');
    }
}
