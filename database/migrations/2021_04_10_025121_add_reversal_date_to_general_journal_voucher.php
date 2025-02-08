<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReversalDateToGeneralJournalVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('general_journal_vouchers', 'reverse_by')) {
                $table->integer('reverse_by')->nullable();
            }
            if (!Schema::hasColumn('general_journal_vouchers', 'reverse_date')) {
                $table->datetime('reverse_date')->nullable();
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
        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            //
        });
    }
}
