<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixesForMainAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('main_accounts', 'main_account_code_number')){
                $table->string('main_account_code_number')->nullable();
            }
            
            if (!Schema::hasColumn('main_accounts', 'main_account_code')){
                $table->string('main_account_code')->nullable();  
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
        //
    }
}
