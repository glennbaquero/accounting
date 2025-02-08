<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxFieldsInVendorInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_invoice_lines', function (Blueprint $table) {
            $table->decimal('less_discount', 9, 2)->default(0);
            $table->decimal('cash_discount', 9, 2)->default(0);
            $table->decimal('add_charge', 9, 2)->default(0);
            $table->decimal('charge', 9, 2)->default(0);
            $table->decimal('add_fee', 9, 2)->default(0);
            $table->decimal('fee', 9, 2)->default(0);
            $table->decimal('line_amount', 9, 2)->default(0);
            $table->decimal('additional_tax', 9, 2)->default(0);
            $table->decimal('vat_amount', 9, 2)->default(0);
            $table->decimal('line_vat', 9, 2)->default(0);
            $table->decimal('total_sales_vat_inclusive', 9, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_invoice_lines', function (Blueprint $table) {
            //
        });
    }
}
