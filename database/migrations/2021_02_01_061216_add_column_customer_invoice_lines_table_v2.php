<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCustomerInvoiceLinesTableV2 extends Migration
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
            if (! Schema::hasColumn(static::$tableName, 'line_status')) {
                $table->string('line_status');
            }

            if (! Schema::hasColumn(static::$tableName,  'quantity')) {
                $table->decimal('quantity', 20, 2)->unsigned()->default(0);
            }

            if (! Schema::hasColumn(static::$tableName, 'unit_price')) {
                $table->decimal('unit_price', 20, 2)->unsigned()->default(0);
            }

            if (! Schema::hasColumn(static::$tableName, 'unit')) {
                $table->decimal('unit', 20, 2)->unsigned()->default(0);
            }
            
            if (! Schema::hasColumn(static::$tableName, 'amount')) {
                $table->decimal('amount', 20, 2)->unsigned()->default(0);
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
            if (Schema::hasColumn(static::$tableName, 'line_status')) {
                $table->dropColumn('line_status');
            }

            if (Schema::hasColumn(static::$tableName,  'quantity')) {
                $table->dropColumn('quantity');
            }

            if (Schema::hasColumn(static::$tableName, 'unit_price')) {
                $table->dropColumn('unit_price');
            }

            if (Schema::hasColumn(static::$tableName, 'unit')) {
                $table->dropColumn('unit');
            }
            
            if (Schema::hasColumn(static::$tableName, 'amount')) {
                $table->dropColumn('amount');
            }
        });
    }
}
