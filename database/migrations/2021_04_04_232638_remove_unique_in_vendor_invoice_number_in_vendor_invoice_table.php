<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUniqueInVendorInvoiceNumberInVendorInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('vendor_invoices', 'vendor_invoice_number')) {
                $table->dropUnique('vendor_invoices_vendor_invoice_number_unique');
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
        Schema::table('vendor_invoices', function (Blueprint $table) {
            //
        });
    }
}
