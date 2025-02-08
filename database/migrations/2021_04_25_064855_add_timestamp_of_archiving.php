<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampOfArchiving extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('closing_transactions', function (Blueprint $table) {

            if (!Schema::hasColumn('closing_transactions', 'archive_inventories_on')) {
                $table->dateTime('archive_inventories_on')->nullable();
            }

            if (!Schema::hasColumn('closing_transactions', 'archive_cash_and_bank_on')){
                $table->dateTime('archive_cash_and_bank_on')->nullable();
            }

            if (!Schema::hasColumn('closing_transactions', 'archive_general_ledger_on')) {
                $table->dateTime('archive_general_ledger_on')->nullable();
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
