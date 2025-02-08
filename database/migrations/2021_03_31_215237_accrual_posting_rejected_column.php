<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccrualPostingRejectedColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accrual_postings', function (Blueprint $table) {
            if (!Schema::hasColumn('accrual_postings', 'rejected_by')) {
                $table->integer('rejected_by')->nullable();
            }

            if (!Schema::hasColumn('accrual_postings', 'rejected_on')) {
                $table->dateTime('rejected_on')->nullable();
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
