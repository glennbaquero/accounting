<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChartOfAccountIdMainAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('main_accounts', 'chart_of_account_id')) {
                $table->integer('chart_of_account_id');
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
        Schema::table('main_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('main_accounts', 'chart_of_account_id')) {
                $table->dropColumn('chart_of_account_id');
            }
        });
    }
}
