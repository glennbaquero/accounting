<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostingProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_invoices', 'posting_profile')) {
                $table->string('posting_profile')->nullable();
            }

            if (!Schema::hasColumn('customer_invoices', 'document')) {
                $table->string('document')->nullable(); //ex.  Invoice
            }

            if (!Schema::hasColumn('customer_invoices', 'document_status')) {
                $table->string('document_status')->nullable(); //ex.  Approved
            }
        });

        Schema::table('customer_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_payments', 'posting_profile')) {
                $table->string('posting_profile')->nullable();
            }

            if (!Schema::hasColumn('customer_payments', 'document')) {
                $table->string('document')->nullable(); //ex.  Invoice
            }
            
            if (!Schema::hasColumn('customer_payments', 'document_status')) {
                $table->string('document_status')->nullable(); //ex.  Approved
            }
        });

        Schema::table('vendor_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_invoices', 'posting_profile')) {
                $table->string('posting_profile')->nullable();
            }

            if (!Schema::hasColumn('vendor_invoices', 'document')) {
                $table->string('document')->nullable(); //ex.  Invoice
            }
            
            if (!Schema::hasColumn('vendor_invoices', 'document_status')) {
                $table->string('document_status')->nullable(); //ex.  Approved
            }
        });

        Schema::table('vendor_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_payments', 'posting_profile')) {
                $table->string('posting_profile')->nullable();
            }

            if (!Schema::hasColumn('vendor_payments', 'document')) {
                $table->string('document')->nullable(); //ex.  Invoice
            }
            
            if (!Schema::hasColumn('vendor_payments', 'document_status')) {
                $table->string('document_status')->nullable(); //ex.  Approved
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
