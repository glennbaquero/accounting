<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovalAndReviewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('closing_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('closing_transactions', 'approved_by')) {
                $table->integer('approved_by')->nullable()->unsigned();
            }
            if (!Schema::hasColumn('closing_transactions', 'approved_on')) {
                $table->dateTime('approved_on')->nullable();
            }
            
            if (!Schema::hasColumn('closing_transactions', 'reviewed_by')) {
                $table->integer('reviewed_by')->nullable()->unsigned();
            }
            if (!Schema::hasColumn('closing_transactions', 'reviewed_on')) {
                $table->dateTime('reviewed_on')->nullable();
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
