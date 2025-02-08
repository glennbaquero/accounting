<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameItemNumberToProductNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'item_number')) {
                $table->renameColumn('item_number', 'product_number');
            }
        });

        Schema::table('variants', function (Blueprint $table) {
            if (!Schema::hasColumn('variants', 'variant_number')) {
                $table->string('variant_number');
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

    }
}
