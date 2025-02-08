<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnV8CustomerInvoiceLinesTable extends Migration
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
            if (! Schema::hasColumn(static::$tableName, 'receive_now_quantity')) {
                $table->integer('receive_now_quantity')->default(0);
            }

            if (! Schema::hasColumn(static::$tableName, 'description')) {
                $table->string('description')->nullable();
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
            if (Schema::hasColumn(static::$tableName, 'receive_now_quantity')) {
                $table->dropColumn('receive_now_quantity');
            }

            if (Schema::hasColumn(static::$tableName, 'description')) {
                $table->dropColumn('description');
            }
        });
    }
}
