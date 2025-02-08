<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClosingPeriodInformationToClosingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('closing_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('closing_transactions', 'closing_status')) {
                $table->string('closing_status')->nullable();
            }

            if (!Schema::hasColumn('closing_transactions', 'closing_period_start')) {
                $table->dateTime('closing_period_start')->nullable();
            }

            if (!Schema::hasColumn('closing_transactions', 'closing_period_end')) {
                $table->dateTime('closing_period_end')->nullable();
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
