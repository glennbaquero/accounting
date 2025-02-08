<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtInLinkedMainAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('linked_main_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('linked_main_accounts', 'deleted_at')) {                    
                $table->softDeletes();
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
        Schema::table('linked_main_accounts', function (Blueprint $table) {
            //
        });
    }
}
