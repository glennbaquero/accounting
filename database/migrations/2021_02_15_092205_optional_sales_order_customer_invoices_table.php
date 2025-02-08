<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OptionalSalesOrderCustomerInvoicesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('customer_invoices', 'sales_order_number')) {
                $table->string('sales_order_number')->nullable()->change();
            }
            
            if (! Schema::hasColumn('customer_invoices', 'customer_address')) {
                $table->string('customer_address');
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
                $table->string('sales_order_number')->index()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'customer_address')) {
                $table->dropColumn('customer_address');
            }
        });
    }
}