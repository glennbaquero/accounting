<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropApprovedByIdTransToInvoiceApprovalJournals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_approval_journal_vouchers', 'approved_by_id_trans')) {
                $table->dropColumn('approved_by_id_trans');
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
