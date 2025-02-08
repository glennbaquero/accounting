<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromissoryNoteVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promissory_note_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_voucher_number')->unique();
            $table->string('promissory_note_journal_number');
            $table->string('invoice_journal_batch_number')->nullable();
            $table->string('journal_name')->nullable();
            $table->string('voucher_line_number');
            $table->date('voucher_date');
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
            $table->string('vendor_invoice_number')->index();
            $table->date('invoice_date');
            $table->string('invoice_number')->nullable();
            $table->date('due_date');
            $table->date('invoice_payment_release_date')->nullable();
            $table->boolean('pending_vendor_invoice')->default(false);
            $table->string('vendor_account');
            $table->string('vendor_name');
            $table->string('payment_id');
            $table->string('method_of_payment');
            $table->string('terms_of_payment');
            $table->string('bank_transaction_type');
            $table->string('bank_account');
            $table->string('payment_specification');
            $table->string('payment_deposit_slip');
            $table->string('purchase_order')->nullable(); 
            $table->string('main_account'); // Max limit of 10 combinations of main accounts, MA+costcentre+dept+vendor+item+etc
            $table->string('account_type')->nullable();
            $table->string('offset_company_accounts')->nullable();
            $table->string('offset_account_type');
            $table->string('offset_account');
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
            $table->string('created_by');
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('promissory_note_vouchers');
    }
}
