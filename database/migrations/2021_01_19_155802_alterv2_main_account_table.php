<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alterv2MainAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_accounts', function (Blueprint $table) {
            $table->string('coa_id')->index()->nullable();
            $table->string('coa_code')->nullable();
            $table->string('coa_name')->nullable();   
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
        });   
    }
}
