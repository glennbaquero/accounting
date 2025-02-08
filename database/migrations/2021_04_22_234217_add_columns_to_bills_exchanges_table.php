<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToBillsExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills_exchanges', function (Blueprint $table) {
            $table->string('bills_of_exchange_stage')->nullable(); // Draw, Redraw, Remit, Settle
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bills_exchanges', function (Blueprint $table) {
            $table->dropColumn('bills_of_exchange_stage');
        });
    }
}
