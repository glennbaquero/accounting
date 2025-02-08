<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogInformationToJournals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoice_approval_vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_invoice_approval_vouchers', 'log_date')) {
                $table->dateTime('log_date')->nullable();
            }
            if (!Schema::hasColumn('customer_invoice_approval_vouchers', 'logged_by')) {
                $table->string('logged_by')->nullable();
            }
        });
        Schema::table('invoice_approval_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_approval_journals', 'log_date')) {
                $table->dateTime('log_date')->nullable();
            }
            if (!Schema::hasColumn('invoice_approval_journals', 'logged_by')) {
                $table->string('logged_by')->nullable();
            }
        });
        Schema::table('customer_payment_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_payment_journals', 'log_date')) {
                $table->dateTime('log_date')->nullable();
            }
            if (!Schema::hasColumn('customer_payment_journals', 'logged_by')) {
                $table->string('logged_by')->nullable();
            }
        });
        Schema::table('vendor_payment_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_payment_journals', 'log_date')) {
                $table->dateTime('log_date')->nullable();
            }
            if (!Schema::hasColumn('vendor_payment_journals', 'logged_by')) {
                $table->string('logged_by')->nullable();
            }
        });

        Schema::table('customer_invoice_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_invoice_journals', 'log_date')) {
                $table->dateTime('log_date')->nullable();
            }
            if (!Schema::hasColumn('customer_invoice_journals', 'logged_by')) {
                $table->string('logged_by')->nullable();
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
        Schema::table('customer_invoice_approval_vouchers', function (Blueprint $table) {
            //
        });
    }
}
