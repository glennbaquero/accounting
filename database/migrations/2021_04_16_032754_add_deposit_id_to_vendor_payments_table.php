<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepositIdToVendorPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->string('deposit_id')->nullable();
        });

        Schema::table('customer_payments', function (Blueprint $table) {
            $table->string('deposit_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->dropColumn('deposit_id');
        });
        
        Schema::table('customer_payments', function (Blueprint $table) {
            $table->dropColumn('deposit_id');
        });
    }
}
