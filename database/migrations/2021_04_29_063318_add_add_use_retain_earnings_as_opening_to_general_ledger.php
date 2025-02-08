<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddUseRetainEarningsAsOpeningToGeneralLedger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_ledgers', function (Blueprint $table) {
            if (!Schema::hasColumn('general_ledgers', 'use_retain_earnings_as_opening')) {
                $table->boolean('use_retain_earnings_as_opening')->default(false);
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
        Schema::table('general_ledgers', function (Blueprint $table) {
            //
        });
    }
}
