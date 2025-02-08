<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeGeneralJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('accrual_postings', 'fiscal_period_start_date')) {
                $table->dateTime('fiscal_period_start_date')->nullable();
            }
            if (!Schema::hasColumn('accrual_postings', 'fiscal_period_end_date')) {
                $table->dateTime('fiscal_period_end_date')->nullable();
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
