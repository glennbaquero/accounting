<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ledger_id')->unique();
            $table->string('ledger_code');
            $table->string('ledger_name');      
            $table->string('ledger_chart_of_accounts');                              
            $table->string('ledger_fiscal_calendar');            
            $table->string('description');            
            $table->string('company_name')->nullable();
            $table->datetime('active_from');
            $table->datetime('active_to');
            $table->string('ledger_status');
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
        Schema::dropIfExists('ledgers');
    }
}
