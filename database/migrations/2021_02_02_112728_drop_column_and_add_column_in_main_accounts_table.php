<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnAndAddColumnInMainAccountsTable extends Migration
{
    private static $tableName = 'main_accounts';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(static::$tableName, function (Blueprint $table) {
            if (Schema::hasColumn(static::$tableName, 'main_account_category')) {
                $table->dropColumn('main_account_category');
            }            
            if (Schema::hasColumn(static::$tableName, 'coa_id')) {
                $table->dropColumn('coa_id');
            }
            if (!Schema::hasColumn(static::$tableName, 'coa_id')) {
                $table->integer('chart_of_account_id')->unsigned();
            }
            if (!Schema::hasColumn(static::$tableName, 'main_account_category_id')) {
                $table->integer('main_account_category_id')->unsigned();
            }
            if (!Schema::hasColumn(static::$tableName, 'company_id')) {
                $table->integer('company_id')->unsigned();
            }
            if (!Schema::hasColumn(static::$tableName, 'client_id')) {
                $table->integer('client_id')->unsigned();
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
        // Schema::table('main_accounts', function (Blueprint $table) {
        //     //
        // });
    }
}
