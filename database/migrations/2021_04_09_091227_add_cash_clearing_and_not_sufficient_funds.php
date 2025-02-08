<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCashClearingAndNotSufficientFunds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_bank_accounts', function (Blueprint $table) {
            $table->string('cash_clearing_account')->nullable();
            $table->string('cash_clearing_account_code')->nullable();

            $table->string('not_sufficient_account')->nullable();
            $table->string('not_sufficient_account_code')->nullable();
            $table->string('credit_limit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_bank_accounts', function (Blueprint $table) {
            $table->dropColumn('cash_clearing_account');
            $table->dropColumn('cash_clearing_account_code');

            $table->dropColumn('not_sufficient_account');
            $table->dropColumn('not_sufficient_account_code');
            $table->dropColumn('credit_limit');

        });
    }
}
