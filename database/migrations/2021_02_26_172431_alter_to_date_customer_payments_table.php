<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterToDateCustomerPaymentsTable extends Migration
{
    private static $tableName = 'customer_payments';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'issue_date')) {
                $table->dropColumn('issue_date');
            }
            if (Schema::hasColumn(static::$tableName, 'payment_release_date')) {
                $table->dropColumn('payment_release_date');
            }
            if (Schema::hasColumn(static::$tableName, 'clearing_date')) {
                $table->dropColumn('clearing_date');
            }
            if (Schema::hasColumn(static::$tableName, 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (Schema::hasColumn(static::$tableName, 'check_number_issued')) {
                $table->dropColumn('check_number_issued');
            }
            if (Schema::hasColumn(static::$tableName, 'maturity_date')) {
                $table->dropColumn('maturity_date');
            }
            if (Schema::hasColumn(static::$tableName, 'received_date')) {
                $table->dropColumn('received_date');
            }
        });
        
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (! Schema::hasColumn(static::$tableName, 'issue_date')) {
                $table->date('issue_date')->default(Carbon::now());
            }
            if (! Schema::hasColumn(static::$tableName, 'payment_release_date')) {
                $table->date('payment_release_date')->nullable()->default(Carbon::now());
            }
            if (! Schema::hasColumn(static::$tableName, 'clearing_date')) {
                $table->date('clearing_date')->nullable()->default(Carbon::now());
            }
            if (! Schema::hasColumn(static::$tableName, 'due_date')) {
                $table->date('due_date')->default(Carbon::now());
            }
            if (! Schema::hasColumn(static::$tableName, 'check_number_issued')) {
                $table->date('check_number_issued')->default(Carbon::now());
            }
            if (! Schema::hasColumn(static::$tableName, 'maturity_date')) {
                $table->date('maturity_date')->default(Carbon::now());
            }
            if (! Schema::hasColumn(static::$tableName, 'received_date')) {
                $table->date('received_date')->nullable()->default(Carbon::now());
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
        Schema::table('customer_payments', function (Blueprint $table) {
            //
        });
    }
}
