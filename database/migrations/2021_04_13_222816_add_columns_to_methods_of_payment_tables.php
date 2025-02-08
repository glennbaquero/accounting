<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToMethodsOfPaymentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payment_methods', function (Blueprint $table) {
            $table->string('document')->nullable();
            $table->string('postdated_check_account')->nullable();
            $table->string('not_sufficient_fund_account')->nullable();
        });

        Schema::table('customer_payment_methods', function (Blueprint $table) {
            $table->string('document')->nullable();
            $table->string('postdated_check_account')->nullable();
            $table->string('not_sufficient_fund_account')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_payment_methods', function (Blueprint $table) {
            $table->dropColumn('document');
            $table->dropColumn('postdated_check_account');
            $table->dropColumn('not_sufficient_fund_account');
        });

        Schema::table('customer_payment_methods', function (Blueprint $table) {
            $table->dropColumn('document');
            $table->dropColumn('postdated_check_account');
            $table->dropColumn('not_sufficient_fund_account');
        });
    }
}
