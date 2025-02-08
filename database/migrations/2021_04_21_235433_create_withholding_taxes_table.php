<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithholdingTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withholding_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();

            // Withholding Tax Posting
            $table->integer('client_id')->nullable();
            $table->string('withholding_tax_posting')->nullable();
            $table->string('withholding_tax_posting_name')->nullable();
            $table->string('description')->nullable();
            $table->dateTime('effective_date')->nullable();
            $table->dateTime('expiration_date')->nullable();
            $table->decimal('withholding_tax_percent', 9, 2)->default(0)->nullable();
            $table->boolean('withholding_tax_exemptions_checkbox')->default(false)->nullable();

            // Main Account
            $table->string('withholding_tax_debit_account')->nullable();
            $table->string('withholding_tax_debit_account_code_number')->nullable();

            $table->string('withholding_tax_credit_account')->nullable();
            $table->string('withholding_tax_credit_account_code_number')->nullable();

            // Offset Account
            $table->string('withholding_tax_debit_offset_account')->nullable();
            $table->string('withholding_tax_debit_offset_account_code_number')->nullable();

            $table->string('withholding_tax_credit_offset_account')->nullable();
            $table->string('withholding_tax_credit_offset_account_code_number')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

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
        Schema::dropIfExists('withholding_taxes');
    }
}
