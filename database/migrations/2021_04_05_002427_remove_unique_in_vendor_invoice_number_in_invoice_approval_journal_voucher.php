<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUniqueInVendorInvoiceNumberInInvoiceApprovalJournalVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_approval_journal_vouchers', 'invoice_voucher_number')) {
                $table->dropUnique('invoice_approval_journal_vouchers_invoice_voucher_number_unique');
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
        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            //
        });
    }
}
