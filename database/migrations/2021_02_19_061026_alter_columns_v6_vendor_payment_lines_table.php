<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsV6VendorPaymentLinesTable extends Migration
{
    private static $tableName = 'vendor_payment_lines';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'vendor_account')) {
                $table->string('vendor_account')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'invoice_account')) {
                $table->string('invoice_account')->nullable()->change();
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
            if (Schema::hasColumn(static::$tableName, 'vendor_account')) {
                $table->string('vendor_account')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'invoice_account')) {
                $table->string('invoice_account')->change();
            }
        });
    }
}
