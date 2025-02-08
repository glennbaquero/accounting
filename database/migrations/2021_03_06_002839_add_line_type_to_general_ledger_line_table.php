<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLineTypeToGeneralLedgerLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_ledger_journal_lines', function (Blueprint $table) {
            $table->string('line_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_ledger_journal_lines', function (Blueprint $table) {
            $table->dropColumn('line_type');
        });
    }
}
