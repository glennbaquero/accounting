<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoReturnNumberInInvoiceAndPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_invoices', function (Blueprint $table) {
            $table->string('purchase_order_return_number')->nullable();
        });
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->string('purchase_order_return_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_invoices', function (Blueprint $table) {
            //
        });
    }
}
