<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDbCrProposalColumnInMainAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('main_accounts', 'db_cr_proposal')){
                $table->string('db_cr_proposal')->nullable();
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
        });
    }
}
