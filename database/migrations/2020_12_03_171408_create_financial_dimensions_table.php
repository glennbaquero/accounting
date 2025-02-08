<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_dimensions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('financial_dimension')->unique();
            // $table->string('department_code')->index();
            $table->string('use_value_from');
            $table->string('dimension_name');
            $table->string('report_column_name');
            $table->string('dimension_value_mask');
            $table->boolean('require_balanced_dimension')->default(false);
            $table->string('require_values_for_the_dimension_to_be_balanced_with')->nullable();

            $table->boolean('custom_name')->nullable();
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
        Schema::dropIfExists('financial_dimensions');
    }
}
