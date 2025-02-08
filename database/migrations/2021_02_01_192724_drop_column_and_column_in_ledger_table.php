<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnAndColumnInLedgerTable extends Migration
{

    private static $tableName = 'ledgers';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'ledger_chart_of_accounts')) {
                $table->dropColumn('ledger_chart_of_accounts');
            }
            if (Schema::hasColumn(static::$tableName, 'ledger_fiscal_calendar')) {
                $table->dropColumn('ledger_fiscal_calendar');
            }
            if (Schema::hasColumn(static::$tableName, 'company_name')) {
                $table->dropColumn('company_name');
            }
            if (Schema::hasColumn(static::$tableName, 'chart_of_accounts_id')) {
                $table->rename('chart_of_accounts_id', 'chart_of_account_id');
            }
            if (!Schema::hasColumn(static::$tableName, 'chart_of_account_id')) {
                $table->integer('chart_of_account_id')->unsigned();
            }
            if (!Schema::hasColumn(static::$tableName, 'ledger_calendar_id')) {
                $table->integer('ledger_calendar_id')->unsigned();
            }
            if (!Schema::hasColumn(static::$tableName, 'company_id')) {
                $table->integer('company_id')->unsigned();
            }
            if (!Schema::hasColumn(static::$tableName, 'client_id')) {
                $table->integer('client_id')->unsigned();
            }
            if (Schema::hasColumn(static::$tableName,'ledger_status')) {
                $table->dropColumn('ledger_status');
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
            //
        });
    }
}
