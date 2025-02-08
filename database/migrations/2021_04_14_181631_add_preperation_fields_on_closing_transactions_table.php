<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreperationFieldsOnClosingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('closing_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('closing_transactions', 'prepared_by')) {
                $table->integer('prepared_by')->nullable()->unsigned();
            }
            if (!Schema::hasColumn('closing_transactions', 'prepared_on')) {
                $table->dateTime('prepared_on')->nullable();
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
