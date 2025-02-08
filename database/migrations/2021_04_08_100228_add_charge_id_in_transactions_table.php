<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChargeIdInTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });

        Schema::table('purchase_order_lines', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });

        Schema::table('vendor_invoices', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });
        
        Schema::table('vendor_invoice_lines', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });

        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });
        
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });
        
        Schema::table('sales_order_lines', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });

        Schema::table('customer_invoices', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });
        
        Schema::table('customer_invoice_lines', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });

        Schema::table('customer_payments', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });
        
        Schema::table('customer_payment_lines', function (Blueprint $table) {
            $table->bigInteger('charge_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_lines', function (Blueprint $table) {
            //
        });
    }
}
