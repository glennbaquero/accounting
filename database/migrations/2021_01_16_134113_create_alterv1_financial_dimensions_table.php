<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlterv1FinancialDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financial_dimensions', function (Blueprint $table) {
            $table->string('dimension_code')->nullable();         
            $table->string('dimension_code_number')->nullable();             

            $table->integer('created_by')->index();  
            $table->integer('updated_by')->nullable()->index();               

            $table->string('use_value_from')->nullable()->change();
            $table->string('dimension_name')->nullable()->change();
            $table->string('report_column_name')->nullable()->change();
            $table->string('dimension_value_mask')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financial_dimensions', function (Blueprint $table) {

        });        
    }
}
