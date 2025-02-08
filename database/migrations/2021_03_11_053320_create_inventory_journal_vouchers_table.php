<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_journal_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inventory_voucher_number')->unique();
            $table->string('inventory_journal_number');
            $table->string('invoice_journal_batch_number');
            $table->string('journal_name');
            $table->string('voucher_line_number');
            $table->date('voucher_date');
            $table->decimal('balance_journal', 20, 9)->default(0);
            $table->decimal('balance_journal_per_voucher', 20, 9)->default(0);
            $table->decimal('total_debit_journal', 20, 9)->default(0);
            $table->decimal('total_credit_journal', 20, 9)->default(0);
            $table->decimal('total_debit_per_voucher', 20, 9)->default(0);
            $table->decimal('total_credit_per_voucher', 20, 9)->default(0);
            $table->decimal('debit_amount', 20, 9)->default(0);
            $table->decimal('credit_amount', 20, 9)->default(0);
            $table->date('approved_date')->nullable();
            $table->string('reported_as_ready_by_journal')->nullable();
            $table->string('approved_by_journal')->nullable();
            $table->string('rejected_by_journal')->nullable();
            $table->string('review_date_trans')->nullable();
            $table->string('approved_by_name_trans')->nullable();
            $table->boolean('posted_checkbox')->default(false);
            $table->date('posted_on')->nullable();
            $table->bigInteger('posted_by')->nullable();
            $table->string('vendor_invoice_number')->nullable();
            $table->string('customer_invoice_number')->nullable();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->date('invoice_payment_release_date')->nullable();
            $table->boolean('pending_vendor_invoice')->default(false);
            $table->string('vendor_account')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('customer_account')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('method_of_payment')->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('bank_transaction_type')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('payment_specification')->nullable();
            $table->string('payment_deposit_slip')->nullable();
            $table->string('purchase_order')->nullable();
            $table->string('main_account')->nullable();
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
            $table->string('calculated_sales_tax_amount')->nullable();
            $table->string('sales_tax_code')->nullable();
            $table->string('sales_tax_direction')->nullable();
            $table->string('sales_tax_group')->nullable();
            $table->string('item_sales_tax_group')->nullable();
            $table->string('actual_tax_amount')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->text('description')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_journal_number')->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->string('logged_by')->nullable();
            $table->text('log_message')->nullable();
            $table->boolean('log_in_checkbox')->default(false);
            $table->date('log_date')->nullable();
            $table->bigInteger('entry_pair_number')->default(0);
            $table->string('approved_by_id_trans')->nullable();
            $table->date('transaction_date')->nullable();

            $table->bigInteger('inventory_on_hand_id')->unsigned()->nullable();
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
        Schema::dropIfExists('inventory_journal_vouchers');
    }
}
