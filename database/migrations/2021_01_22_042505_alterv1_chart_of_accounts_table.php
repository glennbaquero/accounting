<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alterv1ChartOfAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
             $table->string('ledger_id')->nullable();
             $table->string('ledger_code')->nullable();
             $table->string('company_id')->nullable();
             $table->string('company_name')->nullable();             
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {

        });
    }
}
