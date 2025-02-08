<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientInAccountStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_structures', function (Blueprint $table) {
            if (!Schema::hasColumn('account_structures', 'client_id')) {
                   $table->integer('client_id')->index()->nullable();
            } 

            if (!Schema::hasColumn('account_structures', 'company_id')) {
                   $table->string('company_id')->nullable();
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
            //
        });
    }
}
