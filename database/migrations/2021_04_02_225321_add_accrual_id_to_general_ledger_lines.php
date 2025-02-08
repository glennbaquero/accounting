<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccrualIdToGeneralLedgerLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_ledger_journal_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('general_ledger_journal_lines', 'accrual_id')) {
                $table->integer('accrual_id')->unsigned()->nullable();
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
        Schema::table('general_ledger_lines', function (Blueprint $table) {
            //
        });
    }
}
