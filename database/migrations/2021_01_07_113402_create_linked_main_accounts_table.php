<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkedMainAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linked_main_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('linked_main_account_code')->unique();
            $table->string('chart_of_accounts_code');
            $table->string('chart_of_accounts_name');
            $table->string('main_account_code');
            $table->string('main_account');
            $table->string('main_account_type');
            $table->string('main_account_category');                        
            $table->string('linked')->nullable();     
            $table->string('description');        

            $table->integer('created_by')->index();  
            $table->integer('updated_by')->nullable()->index();                                  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linked_main_accounts');
    }
}
