<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientCompanyInLinkedMainAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('linked_main_accounts', function (Blueprint $table) {
            $table->integer('client_id')->index()->nullable();
            $table->integer('company_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('linked_main_accounts', function (Blueprint $table) {
            //
        });
    }
}
