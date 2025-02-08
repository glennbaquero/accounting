<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentReversalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_reversals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();
            
            # Header Information
            $table->integer('client_id')->nullable();

            $table->string('payment_reversal_id')->nullable();
            $table->dateTime('reversed_date')->nullable();
            $table->string('reason')->nullable();
            $table->string('status')->nullable();

            $table->boolean('approved_checkbox')->default(false);
            $table->integer('approved_by')->nullable();
            $table->dateTime('approved_date')->nullable();

            $table->boolean('posted_checkbox')->default(false);
            $table->integer('posted_by')->nullable();
            $table->dateTime('posted_date')->nullable();
            
            $table->string('voucher')->nullable();

            # Bank Information
            $table->string('client_bank_account_number')->nullable();
            $table->string('customer_bank_account_number')->nullable();
            $table->string('vendor_bank_account_number')->nullable();

            # Bank Statement
            $table->string('bank_statement_id')->nullable();
            $table->dateTime('bank_statement_issued_date')->nullable();
            $table->string('bank_statement_status')->nullable();

            # Cash Register
            $table->string('cash_register_id')->nullable();
            $table->dateTime('cash_register_issued_date')->nullable();
            $table->string('cash_register_status')->nullable();

            # Bank Reconciliation
            $table->string('bank_reconciliation_id')->nullable();
            $table->dateTime('bank_reconciliation_issued_date')->nullable();
            $table->string('bank_reconciliation_status')->nullable();

            # Check Information
            $table->string('check_id')->nullable();
            $table->dateTime('check_issued_date')->nullable();
            $table->string('postdated_check_status')->nullable();
            $table->string('check_number')->nullable();
            $table->decimal('amount', 9, 2)->nullable();

            # Deposit Information
            $table->string('deposit_id')->nullable();
            $table->dateTime('deposit_issued_date')->nullable();
            $table->string('deposit_status')->nullable();

            # Vendor Payment
            $table->string('vendor_payment_id')->nullable();
            $table->dateTime('vendor_payment_issued_date')->nullable();
            $table->string('vendor')->nullable();

            # Customer Payment
            $table->string('customer_payment_id')->nullable();
            $table->dateTime('customer_payment_issued_date')->nullable();
            $table->string('customer')->nullable();

            # Audit Fields
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('payment_reversals');
    }
}
