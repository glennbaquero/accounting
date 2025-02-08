<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedAndRejectedDateInJournalHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoice_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_invoice_journals', 'approved_date')) {
                $table->date('approved_date')->nullable();
            } 

             if (!Schema::hasColumn('customer_invoice_journals', 'rejected_date')) {
                $table->date('rejected_date')->nullable();
            }                  

        });

        Schema::table('customer_payment_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_payment_journals', 'approved_date')) {
                $table->date('approved_date')->nullable();
            } 

             if (!Schema::hasColumn('customer_payment_journals', 'rejected_date')) {
                $table->date('rejected_date')->nullable();
            }                  

        });

        Schema::table('invoice_approval_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_approval_journals', 'approved_date')) {
                $table->date('approved_date')->nullable();
            } 

             if (!Schema::hasColumn('invoice_approval_journals', 'rejected_date')) {
                $table->date('rejected_date')->nullable();
            }                  

        });

        Schema::table('vendor_payment_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_payment_journals', 'approved_date')) {
                $table->date('approved_date')->nullable();
            } 

             if (!Schema::hasColumn('vendor_payment_journals', 'rejected_date')) {
                $table->date('rejected_date')->nullable();
            }                  

        });


        Schema::table('general_journals', function (Blueprint $table) {
            if (!Schema::hasColumn('general_journals', 'approved_date')) {
                $table->date('approved_date')->nullable();
            } 

             if (!Schema::hasColumn('general_journals', 'rejected_date')) {
                $table->date('rejected_date')->nullable();
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
        //
    }
}
