<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alterv1AccountStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_structures', function (Blueprint $table) {
             $table->string('chart_of_accounts_id')->nullable();
             $table->string('company_id')->nullable();
             

        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_structures', function (Blueprint $table) {

        });
    }
}
