<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnVendorInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_invoice_lines', function (Blueprint $table) {
            $table->integer('posted_by')->nullable();
            $table->date('posting_date')->nullable();
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
            $table->dropColumn('posted_by');
            $table->dropColumn('posting_date');
        });
    }
}
