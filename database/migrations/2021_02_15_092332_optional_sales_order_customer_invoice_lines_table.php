<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OptionalSalesOrderCustomerInvoiceLinesTable extends Migration
{
    private static $tableName = 'customer_invoice_lines';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'sales_order_number')) {
                $table->string('sales_order_number')->nullable()->change();
            }

            if (! Schema::hasColumn(static::$tableName, 'customer_name')) {
                $table->string('customer_name');
            }

            if (Schema::hasColumn(static::$tableName, 'sales_order_line_number')) {
                $table->string('sales_order_line_number')->nullable()->change();
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
            if (Schema::hasColumn(static::$tableName, 'sales_order_number')) {
                $table->string('sales_order_number')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'customer_name')) {
                $table->string('customer_name');
            }

            if (Schema::hasColumn(static::$tableName, 'sales_order_line_number')) {
                $table->string('sales_order_line_number')->change();
            }
        });
    }
}