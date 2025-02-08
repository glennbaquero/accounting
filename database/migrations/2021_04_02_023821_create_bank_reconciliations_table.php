<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankReconciliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable();

            // Header
            $table->unsignedInteger('client_id')->nullable();
            $table->string('bank_reconciliation_id')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();

            $table->dateTime('reconciled_date')->nullable();
            $table->unsignedInteger('reconciled_by')->nullable();
            $table->boolean('reconciled_checkbox')->default(false);

            $table->dateTime('posted_date')->nullable();
            $table->unsignedInteger('posted_by')->nullable();
            $table->boolean('posted_checkbox')->default(false);

            $table->dateTime('approved_date')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->boolean('approved_checkbox')->default(false);
            
            $table->string('ending_balance')->nullable();
            $table->string('reconciled_transactions')->nullable();
            $table->string('unreconciled_transactions')->nullable();

            // Bank Statement segment
            $table->string('client_bank_account')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_type')->nullable();
            $table->string('bank_statement_id')->nullable();

            $table->dateTime('statement_as_of_date')->nullable();

            $table->string('statement_ending_balance')->nullable();
            $table->string('statement_total_amount')->nullable();
            $table->string('statement_open_amount')->nullable();
            $table->string('balance_per_bank_statement')->nullable();

            // Cash register segment
            $table->string('cash_register_id')->nullable();

            $table->dateTime('cash_register_as_of_date')->nullable();

            $table->string('cash_register_ending_balance')->nullable();
            $table->string('cash_register_total_amount')->nullable();
            $table->string('cash_register_open_amount')->nullable();
            $table->string('balance_per_cash_register')->nullable();
            $table->string('cash_register_description')->nullable();
            
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            
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
        Schema::dropIfExists('bank_reconciliations');
    }
}
