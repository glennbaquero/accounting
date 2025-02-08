<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiscalCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_calendars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fiscal_calendar_id')->unique();            
            $table->string('fiscal_calendar_code')->nullable();
            $table->string('fiscal_calendar_code_number')->nullable();            
            $table->string('fiscal_calendar_name')->nullable();
            $table->longText('fiscal_company_name')->nullable();
            $table->datetime('fiscal_year_start_date')->nullable();
            $table->datetime('fiscal_year_end_date')->nullable();
            $table->integer('length_of_period')->nullable();
            $table->string('unit')->nullable();
            $table->string('fiscal_year_status')->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('fiscal_calendars');
    }
}
