<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alterv2AccountStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_structures', function (Blueprint $table) {
            if (!Schema::hasColumn('account_structures', 'chart_of_accounts_id')) {
                   $table->string('chart_of_accounts_id')->index()->nullable();
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
        Schema::table('account_structures', function (Blueprint $table) {

        });
    }
}
