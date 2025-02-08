<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingCompanyColumnTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_bank_accounts', function (Blueprint $table) {
            $table->bigInteger('company_id');
        });
        Schema::table('vendor_bank_accounts', function (Blueprint $table) {
            $table->bigInteger('company_id');
        });
        Schema::table('customer_bank_accounts', function (Blueprint $table) {
            $table->bigInteger('company_id');
        });
        Schema::table('bank_account_transactions', function (Blueprint $table) {
            $table->bigInteger('company_id');
        });
        Schema::table('bank_account_statements', function (Blueprint $table) {
            $table->bigInteger('company_id');
        });
        Schema::table('deposits', function (Blueprint $table) {
            $table->bigInteger('company_id');
        });
        Schema::table('checks', function (Blueprint $table) {
            $table->bigInteger('company_id');
        });
        Schema::table('bank_reasons', function (Blueprint $table) {
            $table->bigInteger('company_id');
        });
        Schema::table('customer_payment_methods', function (Blueprint $table) {
            $table->bigInteger('company_id');
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
