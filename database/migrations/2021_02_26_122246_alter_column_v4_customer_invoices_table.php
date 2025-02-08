<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnV4CustomerInvoicesTable extends Migration
{
    private static $tableName = 'customer_invoices';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'invoice_date')) {
                $table->date('invoice_date')->change();
            }
            if (Schema::hasColumn(static::$tableName, 'invoiced_by')) {
                $table->string('invoiced_by')->nullable()->change();
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
