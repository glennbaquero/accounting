<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartOfAccountsAccountStructureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_of_accounts_account_structure', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coa_acct_struc_id')->unique();
            // $table->string('coa_account_structure_code' );
            $table->string('coa_acct_struc_code' );
            // $table->string('coa_account_structure_name');
            $table->string('coa_acct_struc_name');
            $table->string('coa_code');
            $table->string('coa_name');
            $table->string('description');

            $table->datetime('active_from');
            $table->datetime ('active_to');
            $table->string('coa_main_acct_status')->default('Active'); // Active and Inactive;            

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
        Schema::dropIfExists('chart_of_accounts_account_structure');
    }
}
