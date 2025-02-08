<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnProtestSettlements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_approval_journals', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_approval_journals', 'protest_settlements')) {
                $table->string('protest_settlements')->nullable()->change();
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
