<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_structures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ledger_id')->index()->nullable();            
            $table->string('ledger_code')->nullable();
            $table->string('ledger_name')->nullable();
            $table->string('ledger_chart_of_accounts')->nullable();
            $table->text('ledger_fiscal_calendar')->nullable();            
            $table->string('company_name')->nullable();            
            $table->string('description')->nullable();
            $table->string('ledger_account_structure_id')->unique();
            $table->string('ledger_account_structure_code_number')->nullable();
            $table->string('ledger_account_structure_code')->nullable();            
            $table->string('ledger_account_structure_name')->nullable();            
            $table->string('main_account_from')->nullable();
            $table->string('main_account_to')->nullable();
            $table->boolean('ledger_status')->default(false);
            $table->datetime('active_from')->nullable();
            $table->datetime('active_to')->nullable();            
            
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
        Schema::dropIfExists('account_structures');
    }
}
