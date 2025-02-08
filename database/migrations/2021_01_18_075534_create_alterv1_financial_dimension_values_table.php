<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlterv1FinancialDimensionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('financial_dimension_values', function (Blueprint $table) {
            $table->string('dimension_value_code')->nullable();
            $table->string('dimension_value_name')->nullable();
            $table->string('dimension_value_code_number')->nullable();            
            $table->integer('created_by')->index();  
            $table->integer('updated_by')->nullable()->index();               

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financial_dimension_values', function (Blueprint $table) {
            $table->dropColumn('dimension_value')->nullable();

        });        
    }
}
