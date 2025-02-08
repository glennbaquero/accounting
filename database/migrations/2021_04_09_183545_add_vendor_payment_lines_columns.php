<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorPaymentLinesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_payment_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_payment_lines', 'vendor_invoice_line_number')) {
                $table->string('vendor_invoice_line_number')->nullable();
            }
            if (!Schema::hasColumn('vendor_payment_lines', 'product')) {
                $table->json('product');
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
