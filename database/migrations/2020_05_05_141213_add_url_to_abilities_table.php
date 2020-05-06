<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUrlToAbilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('abilities', function (Blueprint $table) {
            $table->boolean('use_url')->after('icon')->default(0)->comment('是否使用链接');
            $table->string('url')->after('alias')->nullable()->comment('链接');
            $table->string('alias')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('abilities', function (Blueprint $table) {
            $table->dropColumn('use_url');
            $table->dropColumn('url');
            $table->string('alias')->nullable(false)->change();
        });
    }
}
