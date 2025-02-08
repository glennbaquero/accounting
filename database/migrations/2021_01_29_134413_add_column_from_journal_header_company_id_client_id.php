<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFromJournalHeaderCompanyIdClientId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoice_journals', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('customer_payment_journals', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('general_journals', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('invoice_approval_journals', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('vendor_payment_journals', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('bill_of_exchanges', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->integer('client_id')->nullable();
        });

        Schema::table('promissory_notes', function (Blueprint $table) {
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
        Schema::table('customer_invoice_journals', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });
        Schema::table('customer_payment_journals', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });
        Schema::table('general_journals', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });
        Schema::table('invoice_approval_journals', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });
        Schema::table('vendor_payment_journals', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('client_id');
        });
    }
}
