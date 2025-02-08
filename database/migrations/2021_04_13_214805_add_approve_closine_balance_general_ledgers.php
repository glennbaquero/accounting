<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApproveClosineBalanceGeneralLedgers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_ledgers', function (Blueprint $table) {
            if (!Schema::hasColumn('general_ledgers', 'approve_closing_balance_date')) {
                $table->dateTime('approve_closing_balance_date')->nullable();
            }
            if (!Schema::hasColumn('general_ledgers', 'approve_closing_balance_date_by')) {
                $table->integer('approve_closing_balance_date_by')->unsigned()->nullable();
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
