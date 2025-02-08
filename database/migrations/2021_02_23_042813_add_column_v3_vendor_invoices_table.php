<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnV3VendorInvoicesTable extends Migration
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
            if (Schema::hasColumn(static::$tableName, 'invoice_date')) {
                $table->date('invoice_date')->change();
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
