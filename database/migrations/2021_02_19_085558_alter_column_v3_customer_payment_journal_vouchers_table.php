<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnV3CustomerPaymentJournalVouchersTable extends Migration
{
    private static $tableName = 'customer_payment_journal_vouchers';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'customer_account')) {
                $table->string('customer_account')->nullable()->change();
            }

            if (Schema::hasColumn(static::$tableName, 'customer_name')) {
                $table->string('customer_name')->nullable()->change();
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
            if (Schema::hasColumn(static::$tableName, 'customer_account')) {
                $table->string('customer_account')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'customer_name')) {
                $table->string('customer_name')->change();
            }

            if (Schema::hasColumn(static::$tableName, 'payee')) {
                $table->dropColumn('payee');
            }
        });
    }
}
