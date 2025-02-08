<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_account_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');

            $table->string('client_bank_account_number')->nullable();
            $table->string('customer_bank_account_number')->nullable();
            $table->string('vendor_bank_account_number')->nullable();

            $table->string('method_of_payment_customer')->nullable();
            $table->string('method_of_payment_vendor')->nullable();

            $table->string('vendor_company')->nullable();
            $table->string('vendor_contact')->nullable();

            $table->dateTime('bank_statement_date')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->boolean('cleared_checkbox')->default(false);
            $table->boolean('reconciled_checkbox')->default(false);
            $table->boolean('manual_checkbox')->default(false);
            $table->boolean('pending_cancellation_checkbox')->default(false);
            
            $table->string('bank_statement')->nullable();
            $table->string('deposit_slip_number')->nullable();
            $table->string('check_number')->nullable();
            $table->string('voucher_number')->nullable();
            $table->string('accounting_date')->nullable();
            $table->string('issued_by')->nullable();
            $table->string('bank_posting_profile')->nullable();
            
            $table->string('reason_code')->nullable();
            $table->string('reason_comment')->nullable();

            $table->string('cost_center')->nullable();
            $table->string('department')->nullable();
            $table->string('expense_purpose')->nullable();

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
        Schema::dropIfExists('bank_account_transactions');
    }
}
