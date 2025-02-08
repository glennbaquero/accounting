<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCompanyAndClientInVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('vendor_payment_journal_vouchers', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('customer_payment_journal_vouchers', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('bill_of_exchange_vouchers', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('promissory_note_vouchers', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });

        Schema::table('vendor_payment_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });

        Schema::table('invoice_approval_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });

        Schema::table('general_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });

        Schema::table('customer_payment_journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });

        Schema::table('bill_of_exchange_vouchers', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });

        Schema::table('promissory_note_vouchers', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });
    }
}
