<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUseProcurementToTransactionPosting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_postings', function (Blueprint $table) {
            if (!Schema::hasColumn('transaction_postings', 'use_procurement_account')) {
                $table->boolean('use_procurement_account')->default(false);
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
        Schema::table('transaction_posting', function (Blueprint $table) {
            //
        });
    }
}
