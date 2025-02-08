<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingNewColumnInCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('type_of_trade')->nullable();
            $table->string('major_industry_classification')->nullable();
            $table->string('industry_classification_group')->nullable();
            $table->string('psic_sections')->nullable();
            $table->string('psic_divisions')->nullable();
            $table->string('psic_groups')->nullable();
            $table->string('psic_class')->nullable();
            $table->string('psic_subclass')->nullable();
            $table->boolean('peza_checkbox')->default(false);
            $table->string('vat_exempt_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
