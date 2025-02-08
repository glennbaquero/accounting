<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransferVouchersToCustomerInvoiceApprovalJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoice_approval_vouchers', function (Blueprint $table) {
            $table->string('invoice_journal_batch_number')->nullable();
            $table->string('journal_name')->nullable();
            $table->string('voucher_line_number')->nullable();
            $table->date('voucher_date')->nullable();
            $table->decimal('balance_journal', 9, 2)->default(0);
            $table->decimal('balance_journal_per_voucher', 9, 2)->default(0);
            $table->decimal('total_debit_journal', 9, 2)->default(0);
            $table->decimal('total_credit_journal', 9, 2)->default(0);
            $table->decimal('total_debit_per_voucher', 9, 2)->default(0);
            $table->decimal('total_credit_per_voucher', 9, 2)->default(0);
            $table->text('description')->nullable();
            $table->decimal('debit_amount', 9, 2)->default(0);
            $table->decimal('credit_amount', 9, 2)->default(0);
            $table->date('approved_date')->nullable();
            $table->string('reported_as_ready_by_journal')->nullable();
            $table->string('approved_by_journal')->nullable();
            $table->string('rejected_by_journal')->nullable();
            $table->string('review_date_trans')->nullable();
            $table->string('approved_by_id_trans')->nullable();
            $table->string('approved_by_name_trans')->nullable();
            $table->boolean('posted_checkbox')->default(false);
            $table->date('posted_on')->nullable();
            $table->integer('posted_by')->nullable(); // user id
            $table->string('customer_invoice_number')->index()->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('invoice_payment_release_date')->nullable();
            $table->boolean('pending_customer_invoice')->default(false);
            $table->string('customer_account')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('method_of_payment')->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('bank_transaction_type')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('payment_specification')->nullable();
            $table->string('payment_deposit_slip')->nullable();
            $table->string('sales_order')->nullable(); 
            $table->string('main_account')->nullable(); // Max limit of 10 combinations of main accounts, MA+costcentre+dept+vendor+item+etc
            $table->string('account_type')->nullable();
            $table->string('offset_company_accounts')->nullable();
            $table->string('offset_account_type')->nullable();
            $table->string('offset_account')->nullable();
            $table->string('offset_transaction_text')->nullable();
            $table->string('charges_percentage')->nullable();
            $table->string('cash_discount_code')->nullable();
            $table->string('cash_discount_date')->nullable();
            $table->string('cash_discount_amount')->nullable();
            $table->string('release_date_comment')->nullable();
            $table->string('tax_exempt_number')->nullable();
            $table->string('sales_tax_included_in_amount')->nullable();
            $table->string('calculated_sales_tax_amount')->nullable();
            $table->string('sales_tax_code')->nullable();
            $table->string('sales_tax_direction')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->string('item_sales_tax_group')->nullable();
            $table->string('actual_tax_amount')->nullable();
            $table->string('invoice_number')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_invoice_approval_journal_vouchers', function (Blueprint $table) {
            //
        });
    }
}
