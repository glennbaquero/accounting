<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnInInvoiceApprovalJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
           // $table->string('invoice_voucher_number')->unique();
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
            $table->dropColumn('invoice_voucher_number');
        });
    }
}
