<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArchiveGeneralLedgerByToClosingTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('closing_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('closing_transactions', 'archive_general_ledger_by')) {
                $table->integer('archive_general_ledger_by')->nullable()->unsigned();
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
        Schema::table('closing_transactions', function (Blueprint $table) {
            //
        });
    }
}
