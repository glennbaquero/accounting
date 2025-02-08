<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccrualPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::dropIfExists('accrual_periods');
        Schema::create('accrual_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('period_id')->nullable();
            $table->integer('accrual_id')->unsigned()->nullable();
            $table->integer('fiscal_calendar_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('client_id')->unsigned()->nullable();
            $table->dateTime('fiscal_period_start_date')->nullable();
            $table->dateTime('fiscal_period_end_date')->nullable();  
            $table->string('fiscal_month')->nullable();
            $table->string('fiscal_quarter')->nullable();
            $table->string('fiscal_period_status')->nullable();
            $table->string('comments')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->dateTime('created_on')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->string('fiscal_period_type')->nullable();
            $table->dateTime('updated_on')->nullable();
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
        Schema::dropIfExists('accrual_periods');
    }
}
