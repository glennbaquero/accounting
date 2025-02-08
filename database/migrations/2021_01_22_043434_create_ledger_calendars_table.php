<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgerCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger_calendars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ledger_id')->index()->nullable();            
            $table->string('ledger_code')->nullable();
            $table->string('ledger_name')->nullable();
            $table->string('company_id')->nullable();
            $table->string('company_code')->nullable();
            $table->string('ledger_calendar_id')->nullable();
            $table->string('ledger_calendar_code_number')->nullable();            
            $table->string('ledger_calendar_code')->nullable();
            $table->string('ledger_calendar_name')->nullable();
            $table->string('description')->nullable();
            $table->datetime('ledger_calendar_year')->nullable();
            $table->string('fiscal_calendar_code')->nullable();            
            $table->datetime('fiscal_year_start_date')->nullable();
            $table->datetime('fiscal_year_end_date')->nullable();
            $table->string('ledger_calendar_status')->nullable();            
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
        Schema::dropIfExists('ledger_calendars');
    }
}