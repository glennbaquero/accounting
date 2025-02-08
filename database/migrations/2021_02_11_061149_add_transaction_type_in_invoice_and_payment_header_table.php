<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionTypeInInvoiceAndPaymentHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
            $table->string('transaction_type')->default('Both');
        });

        Schema::table('customer_payments', function (Blueprint $table) {
            $table->string('transaction_type')->default('Both');
        });

        Schema::table('vendor_invoices', function (Blueprint $table) {
            $table->string('transaction_type')->default('Both');
        });

        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->string('transaction_type')->default('Both');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
        });

        Schema::table('customer_payments', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
        });

        Schema::table('vendor_invoices', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
        });

        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
        });
    }
}
