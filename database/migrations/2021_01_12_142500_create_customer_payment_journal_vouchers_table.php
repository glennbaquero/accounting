<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPaymentJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payment_journal_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voucher_number')->unique();
            $table->string('customer_payment_journal_number');
            $table->string('invoice_journal_batch_number');
            $table->string('journal_name');
            $table->string('voucher_line_number');
            $table->string('voucher_date')->nullable();
            $table->decimal('balance_journal', 9, 2)->default(0);
            $table->decimal('balance_journal_per_voucher', 9, 2)->default(0);
            $table->decimal('total_debit_journal', 9, 2)->default(0);
            $table->decimal('total_credit_journal', 9, 2)->default(0);
            $table->decimal('total_debit_per_voucher', 9, 2)->default(0);
            $table->decimal('total_credit_per_voucher', 9, 2)->default(0);
            $table->decimal('debit_amount', 9, 2)->default(0);
            $table->decimal('credit_amount', 9, 2)->default(0);
            $table->text('description')->nullable();
            $table->date('approved_date')->nullable();
            $table->string('reported_as_ready_by_journal');
            $table->string('approved_by_journal');
            $table->string('rejected_by_journal');
            $table->date('review_date_trans')->nullable();
            $table->string('approved_by_id_trans');
            $table->string('approved_by_name_trans');
            $table->string('posted_checkbox');
            $table->date('posted_on')->nullable();
            $table->integer('posted_by')->nullable();
            $table->string('posting_profile');
            $table->string('customer_account');
            $table->string('customer_name');
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->date('payment_due_date');
            $table->string('settlement_type');
            $table->string('method_of_payment');
            $table->string('terms_of_payment');
            $table->string('payment_id');
            $table->string('payment_status');
            $table->string('payment_specification')->nullable();
            $table->string('bank_transaction_type');
            $table->string('bank_account');
            $table->boolean('use_deposit_slip_checkox')->default(false);
            $table->string('deposit_slip_number')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('postdated_check_status'); //Open, On hold, Paid, Posted, Cancelled
            $table->string('check_number')->nullable();
            $table->string('check_number_issued')->nullable();
            $table->date('maturity_date')->nullable();
            $table->date('received_date')->nullable();
            $table->string('cashier');
            $table->string('salesperson');
            $table->string('issuing_bank_branch');
            $table->string('issuing_bank_name');
            $table->boolean('stop_payment')->default(false);
            $table->boolean('replacement_check')->default(false);
            $table->boolean('original_check')->default(false);
            $table->decimal('check_amount', 9, 2)->default(0);
            $table->string('recipient_name');
            $table->string('main_account');
            $table->string('account_type');
            $table->string('offset_company_accounts')->nullable();
            $table->string('offset_account_type');
            $table->string('offset_account');
            $table->string('offset_transaction_text')->nullable();
            $table->string('sales_tax_direction')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->string('item_sales_tax_group')->nullable();
            $table->string('withholding_tax_group')->nullable();
            $table->string('fee_account')->nullable();
            $table->string('fee_id')->nullable();
            $table->decimal('fee_amount', 9, 2)->default(0);
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
        Schema::dropIfExists('customer_payment_journal_vouchers');
    }
}
