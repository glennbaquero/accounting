<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableInvoiceJournalNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            if (Schema::hasColumn('invoclice_approval_journal_vouchers', 'invoice_journal_number')) {
                $table->string('invoice_journal_number')->nullable()->change();
            }
            if (!Schema::hasColumn('invoice_approval_journal_vouchers', 'approved_by_id_trans')) {
                $table->string('approved_by_id_trans')->nullable();
            }
            if (Schema::hasColumn('invoice_approval_journal_vouchers', 'approved_by_id_trans')) {
                $table->string('approved_by_id_trans')->nullable()->change();
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
        //
    }
}
