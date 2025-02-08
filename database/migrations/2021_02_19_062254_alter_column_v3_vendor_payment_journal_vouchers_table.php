<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnV3VendorPaymentJournalVouchersTable extends Migration
{
    private static $tableName = 'vendor_payment_journal_vouchers';
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

            if (Schema::hasColumn(static::$tableName, 'vendor_name')) {
                $table->string('vendor_name')->nullable()->change();
            }

            if (! Schema::hasColumn(static::$tableName, 'payee')) {
                $table->string('payee')->nullable();
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
            
            if (Schema::hasColumn(static::$tableName, 'vendor_name')) {
                $table->string('vendor_name')->change();
            }
            
            if (Schema::hasColumn(static::$tableName, 'payee')) {
                $table->dropColumn('payee');
            }
        });
    }
}
