<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnsInFiscalPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiscal_periods', function (Blueprint $table) {
            
            $table->string('fiscal_year_start_date')->change()->nullable();
            $table->string('fiscal_year_end_date')->change()->nullable();
            $table->date('fiscal_period_start_date')->change()->nullable();
            $table->date('fiscal_period_end_date')->change()->nullable();
            $table->integer('company_id')->unsigned()->index();
            $table->integer('client_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fiscal_periods', function (Blueprint $table) {
            //
        });
    }
}
