<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPaymentReversals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_reversals', function (Blueprint $table) {
            $table->string('payment_reference')->nullable();
            $table->string('customer_payment_method')->nullable();
            $table->string('vendor_payment_method')->nullable();
            $table->string('bank_posting')->nullable();
            $table->string('bank_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_reversals', function (Blueprint $table) {
            $table->dropColumn('payment_reference');
            $table->dropColumn('customer_payment_method');
            $table->dropColumn('vendor_payment_method');
            $table->dropColumn('bank_posting');
            $table->dropColumn('bank_reason');
        });
    }
}
