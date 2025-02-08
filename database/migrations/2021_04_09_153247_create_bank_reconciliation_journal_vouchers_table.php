<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankReconciliationJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_reconciliation_journal_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();

            # Customer Payment
            $table->string('customer_payment_id')->nullable();
            $table->dateTime('customer_payment_issued_date')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_payment_method')->nullable();

            # Vendor Payment
            $table->string('vendor_payment_id')->nullable();
            $table->dateTime('vendor_payment_issued_date')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('vendor_payment_method')->nullable();

            # Bank Account
            $table->string('client_bank_account_number')->nullable();

            $table->string('check_id')->nullable();
            $table->string('check_number')->nullable();
            $table->decimal('check_amount', 9, 2)->nullable();

            $table->string('deposit_id')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('bank_account_transaction_id')->nullable();

            # Bank Reconciliation
            $table->string('bank_reconciliation_id')->nullable();
            $table->dateTime('reconcile_date')->nullable();
            $table->boolean('matched_checkbox')->default(false);
            $table->string('statement_adjustment_id')->nullable();
            $table->string('cash_register_adjustment_id')->nullable();
            $table->string('bank_statement_id')->nullable();
            $table->string('bank_posting')->nullable();
            $table->string('bank_reason')->nullable();

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
        Schema::dropIfExists('bank_reconciliation_journal_vouchers');
    }
}
