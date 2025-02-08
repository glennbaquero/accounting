<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartOfAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coa_id')->unique();
            $table->string('coa_code');
            $table->string('coa_name');
            $table->string('main_account_mask');
            $table->string('description');

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
        Schema::dropIfExists('chart_of_accounts');
    }
}
