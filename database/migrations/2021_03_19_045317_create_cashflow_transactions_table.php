<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashflowTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashflow_transactions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('cashflow_transaction_id')->nullable();
            $table->string('cashflow_transaction_name')->nullable();
            
            $table->string('type')->nullable(); // Customer or Vendor
            
            $table->string('vendor_payment_journal_voucher')->nullable(); // From vendor voucher
            $table->string('vendor_payment_journal_number')->nullable(); // From parent journal

            $table->string('customer_payment_journal_voucher')->nullable(); // From customer voucher
            $table->string('customer_payment_journal_number')->nullable(); // From parent journal

            $table->text('description')->nullable();

            $table->date('invoice_date')->nullable();

            $table->date('payment_due_date')->nullable();
            $table->string('settlement_type')->nullable();
            
            $table->string('vendor_account')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('vendor_invoice_number')->nullable(); // Parent vendor invoice number

            $table->string('customer_account')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_invoice_number')->nullable(); // Parent Customer invoice number

            $table->string('method_of_payment_vendor')->nullable();
            $table->string('vendor_payment_id')->nullable(); // Parent vendor payment

            $table->string('method_of_payment_customer')->nullable();
            $table->string('customer_payment_id')->nullable(); // Parent customer payment

            $table->string('payment_specification')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('bank_transaction_type')->nullable();
            $table->string('bank_account')->nullable();

            $table->string('journal_name')->nullable();
            $table->string('voucher_date')->nullable();

            // Status
            $table->date('posted_on')->nullable();
            $table->integer('posted_by')->nullable();
            $table->string('posted_checkbox')->default(false);
            $table->string('postdated_check_status')->nullable(); //Open, On hold, Paid, Posted, Cancelled
            $table->string('payment_status')->nullable();

            $table->decimal('debit_amount', 9, 2)->default(0);
            $table->decimal('credit_amount', 9, 2)->default(0);

            $table->boolean('reconciled_checkbox')->default(false);
            $table->dateTime('reconciled_date')->nullable();
            $table->integer('reconciled_by')->nullable();

            $table->boolean('adjustment_checkbox')->default(false);
            $table->dateTime('adjustment_date')->nullable();
            $table->integer('adjustment_by')->nullable();

            $table->boolean('matched')->default(false);

            // Payment
            $table->string('deposit_slip_number')->nullable();
            $table->string('check_number')->nullable();

            $table->string('check_number_issued')->nullable();

            $table->date('maturity_date')->nullable();
            $table->date('received_date')->nullable();

            $table->string('cashier')->nullable();
            $table->string('salesperson')->nullable();
            $table->string('issuing_bank_branch')->nullable();
            $table->string('issuing_bank_name')->nullable();
            $table->boolean('stop_payment')->default(false);
            $table->boolean('replacement_check')->default(false);
            $table->boolean('original_check')->default(false);
            $table->decimal('check_amount', 9, 2)->default(0);
            $table->string('recipient_name')->nullable();

            // Account
            $table->string('main_account')->nullable();
            $table->string('account_type')->nullable();
            $table->string('offset_company_accounts')->nullable();
            $table->string('offset_account_type')->nullable();
            $table->string('offset_account')->nullable();
            $table->string('offset_transaction_text')->nullable();
            $table->string('fee_account')->nullable();
            $table->string('fee_id')->nullable();
            $table->decimal('fee_amount', 9, 2)->default(0);
            
            // Tax
            $table->string('sales_tax_direction')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->string('withholding_tax_group')->nullable();
            $table->string('item_sales_tax_group')->nullable();

            // $table->string('invoice_journal_batch_number')->nullable();
            // $table->string('voucher_line_number')->nullable();
            // $table->decimal('balance_journal', 9, 2)->default(0);
            // $table->decimal('balance_journal_per_voucher', 9, 2)->default(0);
            // $table->decimal('total_debit_journal', 9, 2)->default(0);
            // $table->decimal('total_credit_journal', 9, 2)->default(0);
            // $table->decimal('total_debit_per_voucher', 9, 2)->default(0);
            // $table->decimal('total_credit_per_voucher', 9, 2)->default(0);
            // $table->date('approved_date')->nullable();
            // $table->string('reported_as_ready_by_journal');
            // $table->string('approved_by_journal');
            // $table->string('rejected_by_journal');
            // $table->date('review_date_trans')->nullable();
            // $table->string('approved_by_id_trans');
            // $table->string('approved_by_name_trans');
            // $table->string('terms_of_payment');
            // $table->boolean('use_deposit_slip_checkox')->default(false);

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
        Schema::dropIfExists('cashflow_transactions');
    }
}
