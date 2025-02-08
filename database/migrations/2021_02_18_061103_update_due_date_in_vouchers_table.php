<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDueDateInVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            $table->date('due_date')->nullable()->change();
            $table->date('due_date')->nullable()->change();
            $table->string('bank_transaction_type')->nullable()->change();
            $table->string('bank_account')->nullable()->change();
        });
        Schema::table('customer_payment_journal_vouchers', function (Blueprint $table) {
            $table->date('payment_due_date')->nullable()->change();
            $table->string('bank_transaction_type')->nullable()->change();
            $table->string('bank_account')->nullable()->change();
            $table->string('issuing_bank_name')->nullable()->change();
        });
        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            $table->date('due_date')->nullable()->change();
            $table->string('bank_transaction_type')->nullable()->change();
            $table->string('bank_account')->nullable()->change();
            $table->string('method_of_payment')->nullable()->change();
            $table->string('terms_of_payment')->nullable()->change();
            $table->string('payment_specification')->nullable()->change();
            $table->string('payment_deposit_slip')->nullable()->change();
        });
        Schema::table('vendor_payment_journal_vouchers', function (Blueprint $table) {
            $table->string('bank_transaction_type')->nullable()->change();
            $table->string('bank_account')->nullable()->change();
            $table->string('issuing_bank_name')->nullable()->change();
        });
        Schema::table('customer_invoice_approval_vouchers', function (Blueprint $table) {
            $table->string('bank_transaction_type')->nullable()->change();
            $table->string('bank_account')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            //
        });
    }
}
