<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingProductSpecificationInLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_lines', function (Blueprint $table) {
            $table->bigInteger('specification_id')->nullable();
        });

        Schema::table('customer_invoice_lines', function (Blueprint $table) {
            $table->bigInteger('specification_id')->nullable();
        });

        Schema::table('customer_payment_lines', function (Blueprint $table) {
            $table->bigInteger('specification_id')->nullable();
        });

        Schema::table('sales_order_lines', function (Blueprint $table) {
            $table->bigInteger('specification_id')->nullable();
        });
        
        Schema::table('vendor_invoice_lines', function (Blueprint $table) {
            $table->bigInteger('specification_id')->nullable();
        });
        
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            $table->bigInteger('specification_id')->nullable();
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
