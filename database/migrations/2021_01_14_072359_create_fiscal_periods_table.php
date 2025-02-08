<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiscalPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fiscal_period_id')->unique();
            $table->string('fiscal_calendar_id')->index();
            $table->string('fiscal_calendar_code')->index();
            $table->datetime('fiscal_year_start_date')->nullable();
            $table->datetime('fiscal_year_end_date')->nullable();
            $table->string('fiscal_company_name')->nullable();

            $table->string('fiscal_period_code')->nullable();
            $table->string('fiscal_period_name')->nullable();
            $table->string('fiscal_period_type')->nullable();
            $table->datetime('fiscal_period_start_date')->nullable();
            $table->datetime('fiscal_period_end_date')->nullable();
            $table->string('fiscal_month')->nullable();
            $table->string('fiscal_quarter')->nullable();
            $table->string('fiscal_period_status')->nullable();
            $table->longText('comments')->nullable();
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
        Schema::dropIfExists('fiscal_periods');
    }
}

