<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArchivedFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('closing_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('closing_transactions', 'archive_payables_by')) {
                $table->integer('archive_payables_by')->unsigned()->nullable();
            }

            if (!Schema::hasColumn('closing_transactions', 'archive_payables_on')) {
                $table->dateTime('archive_payables_on')->nullable();
            }

            if (!Schema::hasColumn('closing_transactions', 'archive_receivable_by')) {
                $table->integer('archive_receivable_by')->unsigned()->nullable();
            }

            if (!Schema::hasColumn('closing_transactions', 'archive_receivable_on')) {
                $table->dateTime('archive_receivable_on')->nullable();
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
