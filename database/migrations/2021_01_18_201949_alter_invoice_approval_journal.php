<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceApprovalJournal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_approval_journals', function (Blueprint $table) {
            $table->decimal('balance_journal', 20, 2)->default(0)->change();
            $table->decimal('total_debit_journal', 20, 2)->default(0)->change();
            $table->decimal('total_credit_journal', 20, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_approval_journal', function (Blueprint $table) {
            //
        });
    }
}
