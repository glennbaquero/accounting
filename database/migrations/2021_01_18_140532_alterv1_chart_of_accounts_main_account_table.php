<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alterv1ChartOfAccountsMainAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chart_of_accounts_main_account', function (Blueprint $table) {

            $table->string('coa_id')->index();
            $table->string('coa_main_account_code' )->nullable()->change();
            $table->string('coa_main_account_name')->nullable()->change();
            $table->string('main_account_type')->nullable()->change();
            $table->string('main_account_category')->nullable()->change();

            $table->string('coa_code')->nullable()->change();
            $table->string('coa_name')->nullable()->change();
            $table->string('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chart_of_accounts_main_account', function (Blueprint $table) {

        });        
    }
}

