<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OptionalProductColumnVendorInvoiceLinesTable extends Migration
{
    private static $tableName = 'vendor_invoice_lines';

    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'item_number')) {
                $table->string('item_number')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'product_id')) {
                $table->integer('product_id')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'variant_id')) {
                $table->integer('variant_id')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'item_name')) {
                $table->string('item_name')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'product')) {
                $table->json('product')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'variant_number')) {
                $table->string('variant_number')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'variant_name')) {
                $table->string('variant_name')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'variant')) {
                $table->json('variant')->nullable()->change();
            }

            if (! Schema::hasColumn(static::$tableName, 'size')) {
                $table->string('size')->nullable();
            }

            if (! Schema::hasColumn(static::$tableName, 'color')) {
                $table->string('color')->nullable();
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
