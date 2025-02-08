<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alterv4MainAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_accounts', function (Blueprint $table) {
            
            if (!Schema::hasColumn('main_accounts', 'main_account_name')) {
               $table->string('main_account_name')->nullable();
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
        //
        //
        });
    }
}
