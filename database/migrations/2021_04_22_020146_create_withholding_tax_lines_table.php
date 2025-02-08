<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithholdingTaxLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withholding_tax_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();

            $table->integer('client_id')->nullable();
            $table->string('withholding_tax_id')->nullable();
            $table->string('withholding_tax_name')->nullable();
            $table->integer('withholding_tax_posting_id')->nullable();
            $table->string('withholding_tax_posting')->nullable();
            $table->string('description')->nullable();

            $table->decimal('minimum_amount', 9, 2)->nullable();
            $table->decimal('maximum_amount', 9, 2)->nullable();
            $table->decimal('tax_percent', 9, 2)->nullable();
            $table->boolean('withholding_tax_exemptions_checkbox')->default(false)->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withholding_tax_lines');
    }
}
