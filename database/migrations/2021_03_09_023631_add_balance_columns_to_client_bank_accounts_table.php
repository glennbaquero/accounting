<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBalanceColumnsToClientBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_bank_accounts', function (Blueprint $table) {
            $table->string('opening_balance')->nullable();
            $table->string('remaining_balance')->nullable();
            $table->string('bank_balance')->nullable();
            $table->string('main_account_id')->nullable();
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
            $table->dropColumn('opening_balance');
            $table->dropColumn('remaining_balance');
            $table->dropColumn('bank_balance');
            $table->dropColumn('main_account_id');
        });
    }
}
