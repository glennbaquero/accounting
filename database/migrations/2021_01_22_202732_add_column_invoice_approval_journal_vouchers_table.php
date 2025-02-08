<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInvoiceApprovalJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
           // $table->string('invoice_approval_journal_number');
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
            $table->dropColumn('invoice_approval_journal_number');
        });
    }
}
