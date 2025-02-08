<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OptionalPurchaseOrderVendorInvoicesTable extends Migration
{
    private static $tableName = 'vendor_invoices';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'purchase_order_number')) {
                $table->string('purchase_order_number')->nullable()->change();
            }
            
            if (! Schema::hasColumn(static::$tableName, 'vendor_name')) {
                $table->string('vendor_name');
            }
            
            if (! Schema::hasColumn(static::$tableName, 'vendor_address')) {
                $table->string('vendor_address');
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
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'purchase_order_number')) {
                $table->string('purchase_order_number')->index()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'vendor_name')) {
                $table->dropColumn('vendor_name');
            }
            
            if (Schema::hasColumn(static::$tableName, 'vendor_address')) {
                $table->dropColumn('vendor_address');
            }
        });
    }
}