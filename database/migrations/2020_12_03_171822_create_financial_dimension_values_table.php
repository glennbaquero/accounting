<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialDimensionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $table->string('chart_of_accounts')->index();
        // $table->string('main_account')->index();
        Schema::create('financial_dimension_values', function (Blueprint $table) {
            $table->increments('id');
            $table->string('financial_dimension_value_code')->unique();
            $table->string('financial_dimension')->index();
            $table->string('dimension_name')->nullable();
            $table->string('dimension_value')->nullable();
            $table->string('description')->nullable();
            $table->string('select_the_level_of_dimension_value_to_display')->nullable();
            $table->string('companies')->nullable();
            $table->date('active_from');
            $table->date('active_to')->nullable();
            $table->string('suspended_checkbox')->nullable();
            $table->string('owner')->nullable();
            $table->string('group_dimension')->nullable();
            $table->string('calculate_total_from_multiple_dimension_values')->nullable();
            $table->string('do_not_allow_manual_entry')->nullable();
            $table->string('invert_sign')->nullable();

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
        Schema::dropIfExists('financial_dimension_values');
    }
}
