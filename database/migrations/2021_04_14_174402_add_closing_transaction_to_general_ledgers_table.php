<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClosingTransactionToGeneralLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_ledgers', function (Blueprint $table) {
            if (!Schema::hasColumn('general_ledgers', 'closing_transaction_id')) {
                $table->integer('closing_transaction_id')->nullable()->unsigned();
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
