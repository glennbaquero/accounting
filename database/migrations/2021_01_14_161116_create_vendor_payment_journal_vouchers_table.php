<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorPaymentJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_payment_journal_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voucher_number')->unique();
            $table->string('vendor_payment_journal_number');
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
            $table->string('vendor_account');
            $table->string('vendor_name');
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
        Schema::dropIfExists('vendor_payment_journal_vouchers');
    }
}




// #attributes: array:84 [
//     "id" => 9
//     "voucher_number" => "1922021-1616163756-4oev"
//     "vendor_payment_journal_number" => "0002"
//     "invoice_journal_batch_number" => "JBN"
//     "journal_name" => "Not Approved Payment"
//     "voucher_line_number" => "1"
//     "voucher_date" => "2021-03-19 22:23:34"
//     "balance_journal" => "0.00"
//     "balance_journal_per_voucher" => "0.00"
//     "total_debit_journal" => "0.00"
//     "total_credit_journal" => "0.00"
//     "total_debit_per_voucher" => "0.00"
//     "total_credit_per_voucher" => "0.00"
//     "debit_amount" => "0.00"
//     "credit_amount" => "0.00"
//     "description" => null
//     "approved_date" => "2021-03-20"
//     "reported_as_ready_by_journal" => "---"
//     "approved_by_journal" => "Accounting User"
//     "rejected_by_journal" => null
//     "review_date_trans" => null
//     "approved_by_id_trans" => null
//     "approved_by_name_trans" => null
//     "posted_checkbox" => null
//     "posted_on" => Illuminate\Support\Carbon @1616185654 {#2499
//       date: 2021-03-20 04:27:34.116660 Asia/Manila (+08:00)
//     }
//     "posted_by" => 2
//     "posting_profile" => null
//     "vendor_account" => "vendor-20210226-0001"
//     "vendor_name" => "Mr Glenn Baquero Suffix"
//     "invoice_number" => "VP-20210303-1614769953"
//     "invoice_date" => "2021-03-19"
//     "payment_due_date" => "2021-03-19"
//     "settlement_type" => "Open transactions"
//     "method_of_payment" => "1"
//     "terms_of_payment" => "Installment Term"
//     "payment_id" => "VP-20210303-1614769953"
//     "payment_status" => null
//     "payment_specification" => null
//     "bank_transaction_type" => "Sales"
//     "bank_account" => "bank-account-20210305-0002"
//     "use_deposit_slip_checkox" => 0
//     "deposit_slip_number" => null
//     "payment_reference" => null
//     "postdated_check_status" => "Open"
//     "check_number" => null
//     "check_number_issued" => null
//     "maturity_date" => null
//     "received_date" => null
//     "cashier" => null
//     "salesperson" => null
//     "issuing_bank_branch" => null
//     "issuing_bank_name" => null
//     "stop_payment" => 0
//     "replacement_check" => 0
//     "original_check" => 0
//     "check_amount" => "0.00"
//     "recipient_name" => null
//     "main_account" => "---"
//     "account_type" => "---"
//     "offset_company_accounts" => null
//     "offset_account_type" => "---"
//     "offset_account" => "---"
//     "offset_transaction_text" => null
//     "sales_tax_direction" => null
//     "sales_tax_group" => null
//     "item_sales_tax_group" => null
//     "withholding_tax_group" => null
//     "fee_account" => null
//     "fee_id" => null
//     "fee_amount" => "0.00"
//     "created_by" => "Accounting User"
//     "updated_by" => null
//     "deleted_at" => null
//     "created_at" => "2021-03-19 22:23:34"
//     "updated_at" => "2021-03-20 04:27:34"
//     "company_id" => null
//     "client_id" => 1
//     "logged_by" => "Accounting User"
//     "log_message" => "Error: Voucher count is not balance."
//     "log_in_checkbox" => 1
//     "log_date" => "2021-03-20"
//     "entry_pair_number" => 12
//     "payee" => null
//     "transaction_date" => "2021-03-19"
//   ]