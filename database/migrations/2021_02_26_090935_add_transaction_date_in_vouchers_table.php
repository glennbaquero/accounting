<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionDateInVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            $table->date('transaction_date')->nullable();
        });
        Schema::table('customer_payment_journal_vouchers', function (Blueprint $table) {
            $table->date('transaction_date')->nullable();
        });
        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            $table->date('transaction_date')->nullable();
        });
        Schema::table('vendor_payment_journal_vouchers', function (Blueprint $table) {
            $table->date('transaction_date')->nullable();
        });
        Schema::table('customer_invoice_approval_vouchers', function (Blueprint $table) {
            $table->date('transaction_date')->nullable();
        });
        Schema::table('promissory_note_vouchers', function (Blueprint $table) {
            $table->date('transaction_date')->nullable();
        });
        Schema::table('bill_of_exchange_vouchers', function (Blueprint $table) {
            $table->date('transaction_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
