<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'color')) {
                $table->dropColumn('color')->nullable();
            }
            if (Schema::hasColumn('products', 'size')) {
                $table->dropColumn('size');
            }
            if (Schema::hasColumn('products', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
            if (Schema::hasColumn('products', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
