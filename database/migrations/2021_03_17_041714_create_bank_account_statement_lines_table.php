<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountStatementLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_account_statement_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('statement_line_id')->nullable();
            $table->string('statement_id')->nullable();

            $table->string('line_number')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('bank_transaction_code')->nullable();
            $table->string('bank_reason')->nullable();
            $table->string('withdrawal_debit_amount')->nullable();
            $table->string('deposit_credit_amount')->nullable();
            
            $table->string('cost_center')->nullable();
            $table->string('department')->nullable();

            $table->boolean('reconciled_checkbox')->default(false);
            $table->dateTime('reconciled_date')->nullable();
            $table->string('reconciled_by')->nullable();

            $table->boolean('adjustment_checkbox')->default(false);
            $table->dateTime('adjustment_date')->nullable();
            $table->string('adjusted_by')->nullable();

            // Vendor
            $table->string('vendor_payment_journal_voucher')->nullable();
            $table->string('vendor_payment_id')->nullable();
            $table->string('vendor_account')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('method_of_payment_vendor')->nullable();

            // Customer
            $table->string('customer_payment_journal_voucher')->nullable();
            $table->string('customer_payment_id')->nullable();
            $table->string('customer_account')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('method_of_payment_customer')->nullable();

            // General Info
            $table->string('bank_reconciliation_id')->nullable();
            $table->string('cashflow_adjustment_id')->nullable();
            $table->string('bank_account_transaction_id')->nullable();
            $table->string('deposit_id')->nullable();
            $table->string('check_id')->nullable();
            $table->string('description')->nullable();
            $table->string('settlement_type')->nullable();
            $table->boolean('matched_checkbox')->default(false);

            // $table->string('invoice_payment_release_date')->nullable();
            // $table->string('payment_status')->nullable();
            // $table->string('terms_of_payment')->nullable();
            // $table->string('bank_account')->nullable();
            
            $table->string('subledger_journal')->nullable();
            $table->string('ledger_account')->nullable();


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
        Schema::dropIfExists('bank_account_statement_lines');
    }
}
