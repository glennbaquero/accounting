<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCreditAmountInCustomerInvoiceJournalVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoice_approval_vouchers', function (Blueprint $table) {
            if (Schema::hasColumn('customer_invoice_approval_vouchers', 'credit_amount')) {
                $table->decimal('credit_amount', 20, 2)->default(0.00)->change();  
            }
            if (Schema::hasColumn('customer_invoice_approval_vouchers', 'debit_amount')) {
                $table->decimal('debit_amount', 20, 2)->default(0.00)->change();
            }
            if (Schema::hasColumn('customer_invoice_approval_vouchers', 'balance_journal')) {
                $table->decimal('balance_journal', 40, 2)->default(0.00)->change();
            } 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_invoice_journal_vouchers', function (Blueprint $table) {
            //
        });
    }
}
