<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientIdToLedgerCalendars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ledger_calendars', function (Blueprint $table) {
            if (!Schema::hasColumn('ledger_calendars', 'client_id')) {
                $table->integer('client_id')->unsigned();
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
        Schema::table('ledger_calendars', function (Blueprint $table) {
            if (!Schema::hasColumn('ledger_calendars', 'client_id')) {
                $table->integer('client_id')->unsigned();
            }
        });
    }
}
