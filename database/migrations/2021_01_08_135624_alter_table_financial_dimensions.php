<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableFinancialDimensions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financial_dimensions', function (Blueprint $table) {
            if (!Schema::hasColumn('financial_dimensions', 'use_value_from')){
                Schema::table('financial_dimensions', function (Blueprint $table){
                    $table->string('use_value_from')->nullable();
                });
            }
            if (!Schema::hasColumn('financial_dimensions', 'financial_dimension')){
                Schema::table('financial_dimensions', function (Blueprint $table){
                    $table->string('financial_dimension')->nullable();
                });
            }
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
            //
        });
    }
}
