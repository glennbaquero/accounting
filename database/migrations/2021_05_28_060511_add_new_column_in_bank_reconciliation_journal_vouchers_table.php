<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInBankReconciliationJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_reconciliation_journal_vouchers', function (Blueprint $table) {
            $table->bigInteger('bank_reconciliation_journal_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_reconciliation_journal_vouchers', function (Blueprint $table) {
            //
        });
    }
}
