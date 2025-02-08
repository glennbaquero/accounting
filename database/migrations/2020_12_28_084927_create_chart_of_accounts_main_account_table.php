<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartOfAccountsMainAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_of_accounts_main_account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coa_main_account_id')->unique();
            $table->string('coa_main_account_code' );
            $table->string('coa_main_account_name');
            $table->string('main_account_type');
            $table->string('main_account_category');
            $table->string('coa_code');
            $table->string('coa_name');
            $table->string('description');
            $table->string('coa_status')->default('Active'); // Active and Inactive;            

            $table->integer('created_by')->index();  
            $table->integer('updated_by')->nullable()->index();   

            $table->softDeletes();
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
        Schema::dropIfExists('chart_of_accounts_main_account');
    }
}
